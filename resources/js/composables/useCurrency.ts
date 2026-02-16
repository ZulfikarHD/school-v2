/**
 * useCurrency — formatting angka ke format Rupiah (Rp).
 *
 * Menggunakan Intl.NumberFormat untuk locale 'id-ID'
 * sehingga separator titik dan koma sesuai standar Indonesia.
 *
 * @example
 * const { formatRupiah, formatCompact } = useCurrency()
 * formatRupiah(1500000)  // "Rp 1.500.000"
 * formatCompact(1500000) // "Rp 1,5 Jt"
 */
export function useCurrency() {
    const formatter = new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0,
        maximumFractionDigits: 0,
    })

    /**
     * Format angka ke Rupiah penuh (Rp 1.500.000).
     *
     * @param amount - Nilai dalam rupiah
     * @returns String terformat
     */
    function formatRupiah(amount: number): string {
        return formatter.format(amount)
    }

    /**
     * Format angka ke Rupiah compact (Rp 1,5 Jt / Rp 2,3 M).
     *
     * @param amount - Nilai dalam rupiah
     * @returns String terformat dengan suffix Jt/M
     */
    function formatCompact(amount: number): string {
        const abs = Math.abs(amount)
        const sign = amount < 0 ? "-" : ""

        if (abs >= 1_000_000_000) {
            const val = abs / 1_000_000_000
            return `${sign}Rp ${formatDecimal(val)} M`
        }

        if (abs >= 1_000_000) {
            const val = abs / 1_000_000
            return `${sign}Rp ${formatDecimal(val)} Jt`
        }

        if (abs >= 1_000) {
            const val = abs / 1_000
            return `${sign}Rp ${formatDecimal(val)} Rb`
        }

        return formatRupiah(amount)
    }

    /**
     * Parse string Rupiah kembali ke angka.
     *
     * @param value - String format Rupiah
     * @returns Angka numerik
     */
    function parseRupiah(value: string): number {
        const cleaned = value.replace(/[^\d,-]/g, "").replace(",", ".")
        return Number(cleaned) || 0
    }

    return {
        formatRupiah,
        formatCompact,
        parseRupiah,
    }
}

function formatDecimal(value: number): string {
    const rounded = Math.round(value * 10) / 10
    return rounded.toLocaleString("id-ID", {
        minimumFractionDigits: 0,
        maximumFractionDigits: 1,
    })
}
