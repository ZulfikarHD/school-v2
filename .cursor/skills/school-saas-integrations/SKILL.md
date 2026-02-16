---
name: school-saas-integrations
description: School SaaS external integrations — payment gateway (Midtrans/Xendit), WhatsApp API (Fonnte/Wablas), rapor PDF generation, Dapodik export. Use when implementing payment flows, WhatsApp messaging, PDF generation, or any external service integration.
---

# School SaaS Integrations

## 1. Payment Gateway (Midtrans Primary)

### Flow
1. Parent selects fees to pay
2. App creates `Payment` record (status: pending)
3. App calls gateway to create transaction (VA/QRIS/ewallet)
4. Gateway returns payment token/URL
5. Parent pays via bank/ewallet
6. Gateway sends webhook on payment success
7. App verifies webhook signature, updates status
8. Queue dispatches receipt notification (WhatsApp + Reverb push)

### Gateway Interface (Swappable Providers)

```php
interface PaymentGateway
{
    public function createTransaction(Payment $payment, string $method): TransactionResult;
    public function verifyWebhook(Request $request): WebhookResult;
    public function checkStatus(string $transactionId): PaymentStatus;
}
```

Implementations: `MidtransGateway`, `XenditGateway` in `app/Services/Integrations/Payment/`

### Webhook Safety
- Verify signature on every webhook (Midtrans: server key hash, Xendit: callback token)
- Idempotent processing: check if payment already processed before updating
- Log ALL webhook payloads in `payment_gateway_logs` table (JSONB)
- Daily `ReconcilePayments` job compares gateway records with local DB
- Payment amounts validated against `student_fees.amount` (prevent tampering)

### Status Polling Fallback
If webhook arrives late or never: status page polls gateway every 30s for 5min. Daily reconciliation catches missed webhooks. Manual verification fallback.

> **Start with Midtrans.** Better docs, wider payment method support, official Laravel SDK.

## 2. WhatsApp Integration (Fonnte Primary)

### Provider Interface

```php
interface WhatsAppProvider
{
    public function sendText(string $phone, string $message): SendResult;
    public function sendTemplate(string $phone, string $template, array $params): SendResult;
    public function getStatus(string $messageId): DeliveryStatus;
}
```

Implementations: `FonnteProvider`, `WablasProvider` in `app/Services/Integrations/WhatsApp/`

### Rate Limiting
| Aspect | Strategy |
|--------|----------|
| Fonnte limit | ~1000 messages/day on basic plan |
| Queue rate | `RateLimited::perMinute(30)` |
| Priority | Absence alerts > Payment reminders > Announcements |
| Blast | `Bus::chain()` with delays between batches |

### Message Templates (Stored in DB, Editable by School)

| Type | Template Example |
|------|-----------------|
| Absence | "Yth. Bapak/Ibu {parent_name}, kami informasikan bahwa {student_name} kelas {class} tidak hadir hari ini ({date}). Status: {status}. - {school_name}" |
| Payment reminder | "Yth. Bapak/Ibu {parent_name}, SPP {student_name} bulan {month} sebesar Rp {amount} belum dibayar. Bayar sekarang: {payment_url}. - {school_name}" |

### Resilience
- Dead letter queue for failed messages
- 3 retries with exponential backoff
- Failed messages visible to admin for manual retry
- Consider SMS fallback for critical absence alerts

### Custom Notification Channel

```php
// app/Notifications/Channels/WhatsAppChannel.php
// Used via: $user->notify(new AbsenceNotification($student, $date));
```

> **Start with Fonnte.** Cheapest, decent reliability. Abstract provider for easy switching.

## 3. Rapor PDF Generation

Heavy operation — 30 rapor PDFs per class can take minutes.

### Batch Processing

```php
$jobs = $students->map(
    fn (Student $student) => new GenerateRaporPdf($student, $semester)
);

Bus::batch($jobs->toArray())
    ->name("rapor-{$classGroup->id}-{$semester->id}")
    ->allowFailures()
    ->then(fn (Batch $batch) => RaporBatchCompleted::dispatch($batch))
    ->dispatch();
```

| Aspect | Strategy |
|--------|----------|
| Queue | Dedicated `rapor` queue with fewer workers, more memory |
| PDF engine | DomPDF (sufficient for Kurikulum Merdeka format) |
| Data prep | Cache grades/attendance before generating PDFs |
| Progress | Real-time via Reverb: "12 of 30 rapor generated..." |
| Retry | `$tries = 3`, `$timeout = 120` seconds per rapor |

## 4. Dapodik Export

Government data export for compliance. Output format matches Dapodik requirements. Queued job, not real-time.

## Additional Resources

- Full architecture document: [architecture.md](../../../docs/architecture.md)
