document.addEventListener("DOMContentLoaded", function () {
    const ticketSelect = document.getElementById("ticket_select");
    const qtySelect = document.getElementById("qty_select");

    if (!ticketSelect || !qtySelect) return;

    ticketSelect.addEventListener("change", function () {
        const selected = this.options[this.selectedIndex];
        const max = selected.getAttribute("data-max");

        qtySelect.innerHTML = '<option value="">-- Pilih Jumlah Tiket --</option>';

        if (max) {
            for (let i = 1; i <= parseInt(max); i++) {
                qtySelect.innerHTML += `<option value="${i}">${i} Tiket</option>`;
            }
        }
    });

    if (ticketSelect.value) {
        ticketSelect.dispatchEvent(new Event('change'));
    }
});
