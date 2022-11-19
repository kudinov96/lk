<div class="hidden">
    <div class="modal-default" id="modal-service-payment">
        <h2>Оплатить услугу</h2>
        <p class="modal-order-title"></p>

        <form id="service-payment">
            @csrf

            <input type="hidden" name="description">
            <input type="hidden" name="frame" value="false">
            <input type="hidden" name="language" value="ru">
            <input type="hidden" name="service_id">
            <input type="hidden" name="service_type">
            <input type="hidden" name="period_id">

            <label>
                <input type="text" placeholder="ФИО плательщика" name="name" required>
            </label>
            <label>
                <input type="text" placeholder="E-mail" name="email" required>
            </label>
            <label>
                <input type="text" placeholder="Контактный телефон" name="phone" required>
            </label>
            <label for="auto_renewal" class="form-field_checkbox">
                <input type="checkbox" id="auto_renewal" placeholder="Автоплатеж" name="auto_renewal" checked>
                <span>Автоплатеж</span>
            </label>

            <input type="submit" value="Оплатить">
        </form>
    </div>
</div>
