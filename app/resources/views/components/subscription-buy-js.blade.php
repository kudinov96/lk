<script>
    $(document).ready(function () {

        let $form = $("#modal-subscription-payment");

        $(document).on("click", ".select-price1__drop-item", function(){
            let $item     = $(this).closest(".item");
            let period_id = $(this).data("period-id");

            $item.find(".item__current-period").data("period-id", period_id);
        });

        $(document).on("click", ".item__buy", function(){
            let $item             = $(this).closest(".item");
            let subscription_id   = $(this).data("subscription-id");
            let period_id         = $item.find(".item__current-period").data("period-id");
            let order_title       = "Заказ подписки \"" + $item.find(".item__title").text() + "\": " + $item.find(".item__current-period").text();

            $form.find(".modal-order-title").html(order_title);
            $form.find('input[name="description"]').val(order_title);
            $form.find('input[name="subscription_id"]').val(subscription_id);
            $form.find('input[name="period_id"]').val(period_id);
        });

        $("#subscription-payment").on("submit", function(e) {
            e.preventDefault();

            let formData = new FormData($(this)[0]);

            $.ajax({
                url: "{{ route("order.create") }}",
                type: "POST",
                processData: false,
                contentType: false,
                dataType: "json",
                data: formData,
                success: function(response) {
                    if (response.success === true) {
                        window.location.replace(response.payment_url);
                    }
                },
            });
        });

    });
</script>
