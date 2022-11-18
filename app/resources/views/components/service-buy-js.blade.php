<script>
    $(document).ready(function () {

        let $form = $("#modal-service-payment");

        $(document).on("click", ".select-price1__drop-item", function(){
            let $item     = $(this).closest(".item");
            let period_id = $(this).data("period-id");

            $item.find(".item__current-period").data("period-id", period_id);
        });

        $(document).on("click", ".item__buy", function(){
            let $item             = $(this).closest(".item");
            let service_id        = $(this).data("service-id");
            let service_type      = $(this).data("service-type");
            let period_id         = $item.find(".item__current-period").data("period-id");
            let order_title       = "";

            $.ajax({
                url: "{{ route("voyager.payment.full-description") }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    service_id,
                    service_type,
                    period_id,
                },
                success: function(response) {
                    if (response.success === true) {
                        order_title = response.data;

                        $form.find(".modal-order-title").html(order_title);
                        $form.find('input[name="description"]').val(order_title);
                        $form.find('input[name="service_id"]').val(service_id);
                        $form.find('input[name="service_type"]').val(service_type);
                        $form.find('input[name="period_id"]').val(period_id);

                        /*$.fancybox({
                            href: '#modal-service-payment',
                            modal: true
                        });*/
                    }
                },
            });
        });

        $("#service-payment").on("submit", function(e) {
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
