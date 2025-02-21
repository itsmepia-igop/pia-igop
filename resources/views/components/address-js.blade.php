
<script>
    $(document).ready(function () {
        const regions = {{ Js::from($regions) }};
        const provinces = {{ Js::from($provinces) }};
        const cities = {{ Js::from($cities) }};
        const barangays = {{ Js::from($barangays) }};
        
        $('#region').on('change', function () {
            var region_code = $(this).val();

            $('#province').val('').trigger('change');
            $('#province').html('<option value="">Select an option</option>');
            if (region_code !== '') {
                var options = provinces.filter((province) => {
                    return province.region_code == region_code;
                });

                $.each(options, function (key, option) {
                    $('#province').append(`<option value="${option.province_code}">${option.province_name}</option>`)
                });
            }
        });
        if ($('#region').data('value')) {
            var region = regions.find((region) => {
                return region.region_code == $("#region").data('value');
            });
            if (!region) {
                region = regions.find((region) => {
                    return region.region_name == $("#region").data('value');
                });
            }
            $('#region').val(region.region_code).trigger('change');
        }

        $('#province').on('change', function () {
            var province_code = $(this).val();

            $('#city').val('').trigger('change');
            $('#city').html('<option value="">Select an option</option>');
            if (province_code !== '') {
                var options = cities.filter((city) => {
                    return city.province_code == province_code;
                });
                $.each(options, function (key, option) {
                    $('#city').append(`<option value="${option.city_code}">${option.city_name}</option>`)
                });
            }
        });
        if ($('#province').data('value')) {
            var province = provinces.find((province) => {
                return province.province_code == $("#province").data('value') && province.region_code == $('#region').val();
            });
            if (!province) {
                province = provinces.find((province) => {
                    return province.province_name == $("#province").data('value');
                });
            }
            $('#province').val(province.province_code).trigger('change');
        }
        
        $('#city').on('change', function () {
            var city_code = $(this).val();

            $('#barangay').val('').trigger('change');
            $('#barangay').html('<option value="">Select an option</option>');
            if (city_code !== '') {
                var options = barangays.filter((barangay) => {
                    return barangay.city_code == city_code;
                });

                $.each(options, function (key, option) {
                    $('#barangay').append(`<option value="${option.brgy_code}">${option.brgy_name}</option>`)
                });
            }
        });
        if ($('#city').data('value')) {
            var city = cities.find((city) => {
                return city.city_code == $("#city").data('value') && city.province_code == $('#province').val();
            });
            if (!city) {
                city = cities.find((city) => {
                    return city.city_name == $("#city").data('value');
                });
            }
            $('#city').val(city.city_code).trigger('change');
        }

        if ($('#barangay').data('value')) {
            var barangay = barangays.find((barangay) => {
                return barangay.brgy_code == $("#barangay").data('value') && barangay.city_code == $('#city').val();
            });
            if (!barangay) {
                barangay = barangays.find((barangay) => {
                    return barangay.brgy_name == $("#barangay").data('value');
                });
            }
            $('#barangay').val(barangay.brgy_code);
        }
    });
</script>