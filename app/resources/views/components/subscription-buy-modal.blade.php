<div class="hidden">
    <div class="modal-default" id="modal-subscription-payment">
        <h2>Оплатить услугу</h2>
        <p class="modal-order-title"></p>

        <form id="subscription-payment">
            @csrf

            <input type="hidden" name="description">
            <input type="hidden" name="frame" value="false">
            <input type="hidden" name="language" value="ru">
            <input type="hidden" name="subscription_id" required>
            <input type="hidden" name="period_id" required>

            <label>
                <input type="text" placeholder="ФИО плательщика" name="name" required>
            </label>
            <label>
                <input type="text" placeholder="E-mail" name="email" required>
            </label>
            <label>
                <input type="text" placeholder="Контактный телефон" name="phone" required>
            </label>

            <input type="submit" value="Оплатить">
        </form>
    </div>
</div>
