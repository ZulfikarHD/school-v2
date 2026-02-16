---
name: school-saas-infrastructure
description: School SaaS infrastructure — Docker Compose setup, deployment pipeline, CI/CD, monitoring, security, performance bottlenecks, scaling strategy. Use when working on Docker configuration, deployment, CI pipelines, security hardening, or performance optimization.
---

# School SaaS Infrastructure

## Docker Compose Structure

```
docker/
├── docker-compose.yml
├── docker-compose.dev.yml
├── docker-compose.prod.yml
├── app/
│   └── Dockerfile              # PHP 8.3 + extensions
├── nginx/
│   ├── Dockerfile
│   └── conf.d/default.conf
├── scheduler/
│   └── Dockerfile              # Same as app, runs schedule:work
└── horizon/
    └── Dockerfile              # Same as app, runs horizon
```

Services: Nginx, Laravel App, Horizon, Scheduler, Reverb, PostgreSQL, Redis, Meilisearch.

## Deployment Phases

### Phase 1: Simple (5-10 Pilot Schools)
Single VPS with Docker Compose.

| Component | Spec | Cost |
|-----------|------|------|
| VPS | 4 vCPU, 8GB RAM, DigitalOcean (SGP1/JKT) | ~$48/mo |
| Storage | DigitalOcean Spaces (250GB) | $5/mo |
| CDN/SSL | Cloudflare Free tier | $0 |
| **Total** | | **~$55/mo** |

### Phase 2+: Scalable (50+ Schools)
- Managed PostgreSQL ($15/mo+)
- Managed Redis ($10/mo+)
- 2+ App server droplets behind DO Load Balancer
- Separate queue worker droplet
- Automated deployments via GitHub Actions

## CI/CD Pipeline (GitHub Actions)

```
1. Lint + PHPStan (static analysis)
2. PHPUnit/Pest (unit + feature tests)
3. Tenant isolation scan (verify BelongsToSchool trait usage)
4. TypeScript strict mode build check
5. yarn build + Docker image build
6. Deploy to VPS (SSH + Docker)
7. php artisan migrate
8. Config/route/view cache clear
9. Restart workers
```

## Security Architecture

### Data Isolation (Most Critical)
- Automated CI test scans all Eloquent models, asserts tenant models use `BelongsToSchool` trait
- Middleware stack: `InitializeTenancy -> EnsureSchoolActive -> CheckSubscription -> Auth -> VerifyRole`
- No raw queries without explicit `school_id` WHERE clause

### Financial Security
- All `payments` changes logged via `spatie/activitylog` with before/after values
- Webhook endpoints verify cryptographic signatures
- Payment amounts validated against `student_fees.amount`
- Manual payment entry requires `payments.verify` permission (bendahara only)
- Daily reconciliation job flags discrepancies

### User Data (UU PDP Compliance)
| Requirement | Implementation |
|------------|---------------|
| Encryption at rest | Student NIK, parent phone: `$casts = ['nik' => 'encrypted']` |
| Signed URLs | S3 files via signed URLs (expire in 30 minutes) |
| Session timeout | 8 hours admin, 30 days parents (remember me) |
| Data portability | Export/deletion capability per school (tenant offboarding) |

### General Security
| Threat | Protection |
|--------|-----------|
| CSRF | Built-in Inertia CSRF protection |
| Brute force | Rate limiting: 5 login attempts/min, 3 OTP attempts/5min |
| XSS | Vue template escaping (default) |
| SQL injection | Eloquent parameterized queries |
| Malicious uploads | Type, size, MIME validation via `spatie/media-library` |
| DDoS | Cloudflare WAF rules |

## Performance Bottlenecks

| # | Bottleneck | Mitigation |
|---|-----------|------------|
| 1 | Morning Attendance Rush (07:00-08:00 WIB) | Bulk upsert `INSERT ON CONFLICT UPDATE`. Optimistic UI. |
| 2 | SPP Payment Dashboard (aggregate 500+ students) | Materialized `student_fee_summaries` table, updated by observer |
| 3 | Rapor Generation (end of semester) | Dedicated `rapor` queue. `Bus::batch`. Pre-cache data. Temporary worker scale-up. |
| 4 | WhatsApp Blast (1000+ messages) | Rate-limited queue. Stagger by school. Priority queues: `notifications-high`, `notifications-low` |
| 5 | Excel Import (1000+ students) | Chunked processing via `ShouldQueue`. Progress tracking via cache key. Validate first, then import. |

## Database Connection Pooling

When total workers exceed 50, use PgBouncer in front of PostgreSQL.
- Configure `DB_POOL_SIZE=20` per worker type
- Each queue worker uses 1 persistent connection

## Monitoring & Operations

| System | Tool | Details |
|--------|------|---------|
| Error Tracking | Sentry (free: 5K errors/mo) | PHP exceptions + Vue errors |
| Uptime | UptimeRobot or Better Uptime | Alert via WhatsApp/Telegram |
| Queue | Horizon dashboard | SuperAdmin only |
| Database | pg_stat_statements | Alert if any query > 500ms |
| Backups | `spatie/laravel-backup` | Daily at 02:00 WIB. Separate S3 bucket. Monthly restore test. |
| Logging | Laravel daily files | Critical events (payments, deletions) also logged to `audit` channel |

## Edge Cases & Resilience

| Edge Case | Solution |
|-----------|----------|
| Teacher loses internet during attendance | `useOnlineStatus` composable, localStorage fallback, Service Worker background sync |
| Payment webhook late/missing | Status polling every 30s for 5min, daily reconciliation, manual verification |
| Two teachers mark same class | `INSERT ON CONFLICT UPDATE`, last write wins, show "last marked by X at Y" |
| Student school transfer | Transfer workflow: source initiates -> target accepts -> record cloned -> original marked "transferred" |
| Academic year rollover | Admin triggers "New Academic Year": class promotions, reset counters, old year = read-only |
| Subscription expires | 7-day grace (banner) -> read-only -> 30 days inaccessible (data preserved). Never auto-delete. |
| WhatsApp provider down | Dead letter queue, 3 retries exponential backoff, admin manual retry UI |

## Additional Resources

- Full architecture document: [architecture.md](../../../docs/architecture.md)
