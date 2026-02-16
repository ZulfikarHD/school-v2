# Epic M9 — Finance — SPP & School Fees (Keuangan)

> **Epic ID:** M9
> **Phase:** 1 (MVP)
> **Priority:** CORE — Revenue Enabler
> **Sprint Target:** Sprint 5–6
> **Total Story Points:** 42 SP
> **Dependencies:** M3 (Students)
> **Blocks:** M11 (Parent Portal — payment view), M13 (BOS Fund), M20 (Analytics)

---

## Epic Overview

Replace cash-based school fee (SPP) collection with a fully digital payment system. Integrates payment gateway (Midtrans), supports multiple payment methods (Virtual Account, QRIS, E-Wallet, convenience store), auto-reconciliation, WhatsApp reminders, and digital receipts. This is the **revenue enabler** — the platform charges 0.5–1% transaction fee on every payment.

---

## User Stories

### US-9.1: Fee Configuration

**As an** Admin/Bendahara,
**I want** to configure fee types and amounts per class, category, or individual student,
**so that** the billing structure matches our school's fee schedule.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Fee types definable: SPP Bulanan, Uang Gedung, Seragam, Kegiatan, Wisuda, Custom
- [ ] Amount configurable per class (e.g., Kelas 7: Rp 300.000/month) or per student (scholarship overrides)
- [ ] Billing period: monthly, one-time, or installment
- [ ] Due date configurable (e.g., 10th of each month)
- [ ] Auto-generate monthly bills for all active students at start of each month
- [ ] Discount/scholarship support (percentage or fixed amount)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `fee_types` migration (school_id, name, type enum, default_amount, billing_cycle, due_day) | Backend | 0.5h |
| 2 | Create `student_fees` migration (school_id, student_id, fee_type_id, amount DECIMAL(15,2), due_date, status, period_month, period_year) | Backend | 0.5h |
| 3 | Create `FeeType` enum (spp_monthly, uang_gedung, seragam, kegiatan, wisuda, custom) | Backend | 0.5h |
| 4 | Create `FeeType`, `StudentFee` models with `BelongsToSchool` | Backend | 0.5h |
| 5 | Create `FeeService` with configure, generate monthly bills, apply discount | Backend | 2h |
| 6 | Create `GenerateMonthlyBills` scheduled job (runs 1st of each month) | Backend | 1.5h |
| 7 | Create `Finance/FeeConfig.vue` — fee type management | Frontend | 2h |
| 8 | Create per-class amount configuration with override per student | Frontend | 2h |

---

### US-9.2: Payment Status Dashboard

**As a** Bendahara,
**I want** a visual overview of who has paid and who hasn't for any given month,
**so that** I can quickly see the school's collection status.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Dashboard shows: total billed, total paid, total outstanding for selected month
- [ ] Student list with payment status (paid, partial, unpaid, overdue)
- [ ] Color-coded: green (paid), yellow (partial), red (overdue), gray (not yet due)
- [ ] Filter by class, fee type, status
- [ ] Summary cards: collection rate %, total Rp collected, total Rp outstanding
- [ ] Dashboard loads in < 3 seconds (uses materialized `student_fee_summaries`)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `student_fee_summaries` materialized table (student_id, total_due, total_paid, outstanding, months_overdue) | Backend | 1h |
| 2 | Create `PaymentObserver` to update summaries on payment changes | Backend | 1h |
| 3 | Create `PaymentDashboardService` with aggregation queries | Backend | 1h |
| 4 | Create `Finance/Dashboard.vue` with summary cards and student payment list | Frontend | 3h |
| 5 | Add filters: class, fee type, month, status | Frontend | 1h |
| 6 | Create print-friendly payment report | Frontend | 0.5h |

---

### US-9.3: Payment History

**As a** Bendahara or Parent,
**I want** to see the complete payment history for a student,
**so that** there's a clear audit trail of all transactions.

**Story Points:** 2
**Priority:** Must

**Acceptance Criteria:**
- [ ] Per-student timeline showing all payments (date, amount, method, status)
- [ ] Filterable by date range and fee type
- [ ] Payment reference number visible
- [ ] Gateway response details accessible (for troubleshooting)
- [ ] Exportable to Excel

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create payment history endpoint with pagination and filters | Backend | 1h |
| 2 | Create `Finance/PaymentHistory.vue` with timeline/table view | Frontend | 1.5h |
| 3 | Add Excel export | Backend | 0.5h |

---

### US-9.4: WhatsApp Payment Reminder

**As the** System,
**I want** to automatically send WhatsApp reminders before payment due dates,
**so that** parents are prompted to pay on time.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Reminder sent 3 days before due date (configurable)
- [ ] Message includes: student name, fee type, amount, due date, payment link
- [ ] Only sent for unpaid fees
- [ ] Overdue reminder sent on the day after due date
- [ ] Admin can trigger manual reminder blast
- [ ] Parents can opt out of reminders

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `SendPaymentReminder` scheduled job (runs daily) | Backend | 1.5h |
| 2 | Create `PaymentReminderNotification` with WhatsApp channel | Backend | 1h |
| 3 | Create reminder settings (days before due, overdue follow-up frequency) | Backend | 0.5h |
| 4 | Create manual reminder trigger for bendahara | Frontend | 1h |
| 5 | Add opt-out mechanism for parents | Backend | 0.5h |

---

### US-9.5: Payment Gateway Integration (Midtrans)

**As the** System,
**I want** to integrate with Midtrans payment gateway for online payment processing,
**so that** parents can pay school fees digitally.

**Story Points:** 8
**Priority:** Must

**Acceptance Criteria:**
- [ ] `PaymentGateway` interface with `MidtransGateway` implementation
- [ ] Create payment transaction → receive redirect URL or VA/QR details
- [ ] Webhook receives payment confirmation → updates payment status
- [ ] Webhook signature verification on every callback
- [ ] Idempotent webhook processing (duplicate callbacks handled)
- [ ] All webhook payloads logged in `payment_gateway_logs` (JSONB)
- [ ] Payment amounts validated against `student_fees.amount` (tampering prevention)

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `PaymentGateway` interface (createTransaction, handleWebhook, checkStatus, refund) | Backend | 1h |
| 2 | Create `MidtransGateway` implementation using official Midtrans PHP SDK | Backend | 4h |
| 3 | Create `payments` migration (school_id, student_fee_id, gateway, transaction_id, amount, method, status, gateway_response JSONB, paid_at) | Backend | 0.5h |
| 4 | Create `payment_gateway_logs` migration (payload JSONB, direction, processed_at) | Backend | 0.5h |
| 5 | Create `PaymentService` (createPayment, processWebhook, manualVerify) | Backend | 2h |
| 6 | Create `PaymentWebhookController` with signature verification | Backend | 1.5h |
| 7 | Create `PaymentStatus` enum (pending, success, failed, expired, refunded) | Backend | 0.5h |
| 8 | Write feature tests with mocked Midtrans API | Backend | 2h |

---

### US-9.6: Virtual Account Payment

**As a** Parent,
**I want** to pay via bank Virtual Account (BCA, BNI, Mandiri, BRI),
**so that** I can transfer from my bank account.

**Story Points:** 2
**Priority:** Must

**Acceptance Criteria:**
- [ ] VA numbers generated for major Indonesian banks (BCA, BNI, Mandiri, BRI)
- [ ] VA number displayed with bank logo and instructions
- [ ] Copy-to-clipboard for VA number
- [ ] VA expiry time shown (24 hours default)
- [ ] Auto-confirmation when bank confirms transfer

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Configure Midtrans VA payment channels | Backend | 1h |
| 2 | Create VA display page with bank logos, copy button, countdown | Frontend | 2h |

---

### US-9.7: QRIS Payment

**As a** Parent,
**I want** to pay via QRIS (universal QR code),
**so that** I can pay using any e-wallet or banking app.

**Story Points:** 2
**Priority:** Must

**Acceptance Criteria:**
- [ ] QR code generated and displayed
- [ ] QR code scannable by any QRIS-compatible app
- [ ] Payment confirmed within seconds of scanning
- [ ] Expiry time shown

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Configure Midtrans QRIS payment channel | Backend | 0.5h |
| 2 | Create QR code display page with auto-refresh on payment confirmation | Frontend | 1.5h |

---

### US-9.8: E-Wallet Payment

**As a** Parent,
**I want** to pay via e-wallet (GoPay, OVO, DANA, ShopeePay),
**so that** I can use my preferred digital wallet.

**Story Points:** 2
**Priority:** Should

**Acceptance Criteria:**
- [ ] E-wallet options shown on payment method selection
- [ ] Redirect to e-wallet app or deeplink
- [ ] Payment confirmation via webhook
- [ ] Available methods: GoPay, OVO, DANA, ShopeePay

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Configure Midtrans e-wallet channels (GoPay, OVO, DANA, ShopeePay) | Backend | 1h |
| 2 | Create e-wallet redirect/deeplink handling | Frontend | 1h |

---

### US-9.9: Convenience Store Payment

**As a** Parent,
**I want** to pay at Alfamart or Indomaret using a payment code,
**so that** I can pay with cash at a nearby store.

**Story Points:** 1
**Priority:** Could

**Acceptance Criteria:**
- [ ] Payment code generated for Alfamart/Indomaret
- [ ] Code displayed with instructions and expiry
- [ ] Payment confirmed via webhook after store processes it

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Configure Midtrans convenience store channels | Backend | 0.5h |
| 2 | Create payment code display page with instructions | Frontend | 1h |

---

### US-9.10: Auto-Reconciliation

**As the** System,
**I want** to automatically reconcile payments when the gateway confirms,
**so that** payment statuses are always accurate.

**Story Points:** 5
**Priority:** Must

**Acceptance Criteria:**
- [ ] Webhook updates payment status within 5 minutes of gateway confirmation
- [ ] Daily `ReconcilePayments` job compares gateway records with local DB
- [ ] Discrepancies flagged for manual review
- [ ] Status page polls gateway every 30s for 5min after payment (fallback for missed webhooks)
- [ ] All reconciliation actions logged

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create webhook processing in `PaymentService` (idempotent, signature-verified) | Backend | 1.5h |
| 2 | Create `ReconcilePayments` daily job (compare gateway API vs local DB) | Backend | 2h |
| 3 | Create payment status polling endpoint (for fallback) | Backend | 1h |
| 4 | Create discrepancy report page for admin | Frontend | 1.5h |
| 5 | Implement status page polling (parent waits for confirmation) | Frontend | 1h |

---

### US-9.11: Digital Receipt

**As the** System,
**I want** to generate and send a digital receipt for every successful payment,
**so that** parents have proof of payment.

**Story Points:** 2
**Priority:** Must

**Acceptance Criteria:**
- [ ] Receipt generated with: school name, student name, payment amount, method, date, transaction ID
- [ ] Receipt viewable in-app (parent portal)
- [ ] Receipt sent via WhatsApp (PDF attachment or message with details)
- [ ] Receipt downloadable as PDF

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create receipt Blade template (branded with school logo) | Backend | 1h |
| 2 | Create `GenerateReceipt` job triggered on payment success | Backend | 1h |
| 3 | Send receipt via WhatsApp notification | Backend | 0.5h |
| 4 | Create receipt view/download in parent portal | Frontend | 1h |

---

### US-9.12: Installment Plans

**As an** Admin,
**I want** to set up installment plans (cicilan) for large one-time fees,
**so that** parents can pay in manageable amounts.

**Story Points:** 3
**Priority:** Should

**Acceptance Criteria:**
- [ ] Admin creates installment plan: total amount, number of installments, dates
- [ ] Each installment becomes a separate student_fee record
- [ ] Payment tracked per installment
- [ ] Outstanding balance shown to parent

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `installment_plans` migration (fee_type_id, total_amount, installments, schedule JSONB) | Backend | 0.5h |
| 2 | Create installment generation logic in `FeeService` | Backend | 1h |
| 3 | Create installment plan configuration UI | Frontend | 1.5h |
| 4 | Display installment schedule on parent portal | Frontend | 0.5h |

---

### US-9.13: Other Fee Types

**As an** Admin,
**I want** to create custom fee types beyond SPP (uang gedung, seragam, kegiatan, wisuda),
**so that** all school payments flow through the platform.

**Story Points:** 1
**Priority:** Must

> Covered by US-9.1 fee configuration. This is a placeholder to ensure flexibility is tested.

**Acceptance Criteria:**
- [ ] Custom fee types can be created with arbitrary names and amounts
- [ ] One-time and recurring billing cycles supported
- [ ] Each fee type appears in the parent portal billing section

---

### US-9.14: Payment Reports

**As a** Bendahara,
**I want** comprehensive financial reports for the school,
**so that** I can report to the principal and school committee.

**Story Points:** 3
**Priority:** Must

**Acceptance Criteria:**
- [ ] Monthly revenue report: total collected, by fee type, by class
- [ ] Outstanding report: who owes what, sorted by overdue duration
- [ ] Payment method breakdown: VA vs QRIS vs e-wallet vs cash
- [ ] Exportable to Excel
- [ ] Printable summary for school committee meetings

**Tasks:**

| # | Task | Layer | Est. Hours |
|---|------|-------|------------|
| 1 | Create `PaymentReportService` with monthly, outstanding, method breakdown queries | Backend | 2h |
| 2 | Create report API endpoints | Backend | 0.5h |
| 3 | Create `Finance/Reports.vue` with charts and tables | Frontend | 2.5h |
| 4 | Add Excel export for all reports | Backend | 1h |

---

## Technical Notes

- **Gateway abstraction**: `PaymentGateway` interface → `MidtransGateway` implementation. Adding Xendit later requires only implementing the interface.
- **Webhook safety**: Every webhook verifies cryptographic signature. All payloads logged in `payment_gateway_logs` JSONB. Idempotent processing (check `transaction_id` before updating).
- **Daily reconciliation**: `ReconcilePayments` job runs at 02:00 WIB, compares Midtrans status API vs local `payments` table.
- **Materialized summary**: `student_fee_summaries` updated by `PaymentObserver` — avoids expensive aggregate queries on dashboard load.
- **All financial changes** logged via `spatie/laravel-activitylog` with before/after values.
- **Platform revenue**: 0.5–1% surcharge on gateway amount (transparent to school, added on top of gateway fees).

## Risks & Mitigations

| Risk | Impact | Mitigation |
|------|--------|------------|
| Payment webhook arrives late or never | Payment not recorded | Status polling fallback (30s × 5min), daily reconciliation catches rest |
| Gateway downtime | Parents can't pay | Show "payment gateway temporarily unavailable", allow manual cash recording by bendahara |
| Payment amount tampering | Financial loss | Validate payment amount against `student_fees.amount` on webhook, reject mismatches |
| Dashboard slow with 500+ students | Bendahara frustrated | Materialized `student_fee_summaries` table, updated incrementally by observer |

---

## Definition of Done (Epic Level)

- [ ] Fee types configurable with amounts per class
- [ ] Monthly bills auto-generated for all students
- [ ] Dashboard shows real-time payment status per student
- [ ] At least 3 payment methods work (VA, QRIS, E-Wallet)
- [ ] Payment auto-reconciled within 5 minutes of gateway confirmation
- [ ] WhatsApp reminders sent 3 days before due date
- [ ] Digital receipt generated for every successful payment
- [ ] Financial reports exportable to Excel
- [ ] All financial data has audit trail
- [ ] Gateway abstraction allows switching providers without code changes

---

### Related Files

- **Previous:** [`M08_RAPOR.md`](M08_RAPOR.md)
- **Next:** [`M10_COMMUNICATION.md`](M10_COMMUNICATION.md)
- **Feature Doc Reference:** `FEATURE_DOCUMENT.md` § M9
