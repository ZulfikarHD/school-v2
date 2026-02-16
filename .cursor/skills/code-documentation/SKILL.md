---
name: code-documentation
description: Creates and maintains professional code documentation following Google/Microsoft engineering standards. Covers inline comments, PHPDoc/JSDoc, README files, and architecture docs. Use when writing documentation, creating new files/features, reviewing code for documentation quality, or when the user mentions documentation, comments, README, or onboarding.
---

# Code Documentation Standards

Documentation lives WITH the code, not separate from it.

## When to Document (Decision Tree)

```
Creating/editing code?
│
├─► Public method/function ──────► PHPDoc/JSDoc block (ALWAYS)
├─► Complex "WHY" logic ─────────► Inline comment (ALWAYS)
├─► New class/service ───────────► Class-level docblock (ALWAYS)
├─► New Vue component ───────────► Script-level JSDoc (ALWAYS)
│
├─► New feature folder?
│   └─► Has 5+ files? ───────────► Module README.md (YES)
│   └─► Has <5 files? ───────────► No README needed (NO)
│
├─► Architecture changed? ───────► Update docs/architecture.md
│
└─► Sprint/epic completed?
    └─► Check all above ✓ ───────► Done. No separate docs needed.
```

## What NOT to Create

| Don't Create | Why |
|--------------|-----|
| `docs/features/orders/` | Becomes outdated, use inline docs instead |
| `docs/services/x.md` | Document in the service file itself |
| `docs/sprint-5/` | Not useful, becomes dead documentation |
| Separate API docs | Unless you have external API consumers |

---

## Documentation Types

### 1. Inline Comments (WHY, not WHAT)

```php
// ✅ GOOD - Explains WHY
// Skip validation for system-generated orders yang sudah
// diverifikasi di external API sebelum masuk ke sistem
if ($order->is_system_generated) { return true; }

// ❌ BAD - Explains WHAT (obvious)
// Check if order is system generated
if ($order->is_system_generated) { return true; }
```

### 2. Class/Service Docblock

```php
/*
|--------------------------------------------------------------------------
| Order Service
|--------------------------------------------------------------------------
|
| Service layer untuk business logic Order, yang mencakup:
| CRUD operations, status management, dan kalkulasi metrics.
|
*/
class OrderService { }
```

### 3. Method Docblock

```php
/**
 * Menghitung production metrics untuk order.
 *
 * @param Order $order Order yang akan dihitung
 * @return array{perfect_prints: int, defect_rate: float}
 * @throws InvalidOrderStateException Jika order cancelled
 */
public function calculateMetrics(Order $order): array { }
```

### 4. Vue Component JSDoc

```vue
<script setup lang="ts">
/**
 * OrderTable - Data table untuk daftar order produksi.
 *
 * Features: virtual scrolling, sorting, filtering, row selection.
 */

interface Props {
  /** Array order yang ditampilkan */
  orders: Order[]
  /** State loading untuk skeleton UI */
  loading?: boolean
}
</script>
```

### 5. TypeScript Types

```typescript
/**
 * Order entity - unit utama tracking produksi.
 */
interface Order {
  /** Nomor PO - unique business identifier */
  po_number: number
  /** Status dalam production pipeline */
  status: OrderStatus
}
```

---

## Module README (Only if 5+ files)

Only create `README.md` for complex modules:

```markdown
# Orders Module

Module untuk order produksi Pita Cukai.

## Files

| File | Purpose |
|------|---------|
| `Index.vue` | List dengan filter dan pagination |
| `Show.vue` | Detail view single order |
| `Partials/` | Feature-specific components |
```

---

## Sprint Completion Checklist

```
After completing sprint/feature:

[ ] All new Services have class-level docblock
[ ] All public methods have PHPDoc/JSDoc
[ ] Complex WHY logic has inline comments  
[ ] Vue components have script-level JSDoc
[ ] Types have property descriptions
[ ] If 5+ files in module → add README
[ ] If architecture changed → update docs/architecture.md

Done. No separate feature documentation needed.
```

---

## Anti-Patterns

| Avoid | Better |
|-------|--------|
| `// increment i` | No comment - code is clear |
| `// fix bug` | `// Prevent negative qty (bug #123)` |
| `// TODO: fix this` | `// TODO: Handle edge case X when Y` |
| Docblock doesn't match signature | Keep docblock in sync with code |
| Comment every line | Only comment non-obvious WHY |

---

## Quick Reference

### PHPDoc Template

```php
/**
 * [One-line description]
 *
 * @param Type $name Description
 * @return Type Description  
 * @throws Exception When condition
 */
```

### JSDoc Template

```typescript
/**
 * [One-line description]
 *
 * @param name - Description
 * @returns Description
 * @example
 * functionName('value')
 */
```

## Additional Resources

- Detailed examples: [examples.md](examples.md)
- Copy-paste templates: [templates.md](templates.md)
