/**
 * useOnlineStatus — deteksi status koneksi internet user.
 *
 * Penting untuk fitur attendance marking di mana guru
 * bisa kehilangan koneksi saat di kelas. Mendukung
 * penyimpanan data ke localStorage saat offline.
 *
 * @example
 * const { isOnline, wasOffline } = useOnlineStatus()
 *
 * watch(isOnline, (online) => {
 *   if (online && wasOffline.value) {
 *     syncPendingData()
 *   }
 * })
 */
import { ref, onMounted, onUnmounted } from "vue"

export function useOnlineStatus() {
    const isOnline = ref(true)
    const wasOffline = ref(false)

    function handleOnline(): void {
        isOnline.value = true
    }

    function handleOffline(): void {
        isOnline.value = false
        wasOffline.value = true
    }

    onMounted(() => {
        isOnline.value = navigator.onLine
        window.addEventListener("online", handleOnline)
        window.addEventListener("offline", handleOffline)
    })

    onUnmounted(() => {
        window.removeEventListener("online", handleOnline)
        window.removeEventListener("offline", handleOffline)
    })

    /**
     * Reset flag wasOffline setelah sync selesai.
     */
    function resetOfflineFlag(): void {
        wasOffline.value = false
    }

    return {
        /** Apakah device sedang online */
        isOnline,
        /** Apakah device pernah offline sejak mount */
        wasOffline,
        /** Reset flag wasOffline */
        resetOfflineFlag,
    }
}
