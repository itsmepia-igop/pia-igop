<script>
    $(document).ready(function () {
        $('.repeater .btn-add').on('click', function () {
            var repeater = $(this).closest('.repeater');
            var list = repeater.find('.list');
            var item = list.find('.item:last-child').clone();
                item.find('.form-control').val('');
                item.find('.form-select').val('');
            
            list.append(item);
            repeater.find('.btn-remove').removeClass('d-none');
        });

        $('.repeater .btn-remove').on('click', function () {
            var repeater = $(this).closest('.repeater');
            if ($('.list .item', repeater).length == 1) {
                return;
            }

            $('.list .item:last-child', repeater).remove();
            if ($('.list .item', repeater).length == 1) {
                $(this).addClass('d-none');
            }
        });
    });
</script>