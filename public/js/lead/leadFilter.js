$(document).ready(function () {

    let scriptUi = '<script id="jquery_uiScript" src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"\n' +
        '            integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>';



    function startLeadFilter() {
        $.ajax({
            url: '/startLeadFilter',
            method: 'post',
            success: function (result) {
                if (result.role == 1 || result.role == 5) {
                    generationGroupSelector(result.value)

                    $('#leadGroupSelector').change(function () {
                        $.ajax({
                            method: 'post',
                            url: '/secondLeadFilter',
                            data: {group_id: $('#leadGroupSelector').val()},
                            success: function (resultTwo) {
                                $('#sectorRegionManagerFilter').html('');
                                $('#sectorSalonFilter').html('');
                                $('#sectorManagerFilter').html('');
                                if ($('#leadGroupSelector').val() != 1 && $('#leadGroupSelector').val() != 3) {
                                    generationRegionManagerSelector(resultTwo);

                                    $('#leadRegionManagerSelector').change(function () {
                                        $.ajax({
                                            method: 'post',
                                            url: '/secondLeadFilter',
                                            data: {regionManager_id: $('#leadRegionManagerSelector').val()},
                                            success: function (resultTree) {
                                                $('#sectorSalonFilter').html('');
                                                if (resultTree.length != 0) {
                                                    generationSalonSelector(resultTree)

                                                    $('#leadSalon').change(function (e) {
                                                        e.preventDefault();
                                                        $.ajax({
                                                            method: 'post',
                                                            url: '/secondLeadFilter',
                                                            data: {salon_id: $('#leadSalon').val()},
                                                            success: function (resultFour) {
                                                                $('#sectorManagerFilter').html('');
                                                                if (resultFour.length != 0) {
                                                                    generationManagerSelector(resultFour);
                                                                }

                                                            }
                                                        })
                                                    })
                                                }
                                            }
                                        })
                                    })

                                }
                                if ($('#leadGroupSelector').val() == 1) {
                                    generationManagerSelector(resultTwo)
                                }
                            }
                        })
                    })
                } else if (result.role == 2) {
                    generationRegionManagerSelector(result.value)

                    $('#leadRegionManagerSelector').change(function () {
                        $.ajax({
                            method: 'post',
                            url: '/secondLeadFilter',
                            data: {regionManager_id: $('#leadRegionManagerSelector').val()},
                            success: function (resultTree) {
                                // console.log(resultTree)
                                $('#sectorSalonFilter').html('');
                                if (resultTree.length != 0) {
                                    generationSalonSelector(resultTree)

                                    $('#leadSalon').change(function (e) {
                                        e.preventDefault();
                                        $.ajax({
                                            method: 'post',
                                            url: '/secondLeadFilter',
                                            data: {salon_id: $('#leadSalon').val()},
                                            success: function (resultFour) {
                                                $('#sectorManagerFilter').html('');
                                                if (resultFour.length != 0) {
                                                    generationManagerSelector(resultFour);
                                                }

                                            }
                                        })
                                    })
                                }
                            }
                        })
                    })

                } else if (result.role == 4) {
                    generationSalonSelector(result.value);

                    $('#leadSalon').change(function (e) {
                        e.preventDefault();
                        $.ajax({
                            method: 'post',
                            url: '/secondLeadFilter',
                            data: {salon_id: $('#leadSalon').val()},
                            success: function (resultTwo) {
                                $('#sectorManagerFilter').html('');
                                if (resultTwo.length != 0) {
                                    generationManagerSelector(resultTwo);
                                }

                            }
                        })
                    })
                }

            }
        })
    }

    startLeadFilter();

    function generationGroupSelector(result) {
        $('#sectorGroupFilter').html(
            `<div class="filter filter__group">
                 <div class="filtter__title">
                     <span class='title'>Группа</span>
                 </div>
                 <div class="filter__content">
                     <label>
                         <select name="group_id" id="leadGroupSelector" class="multiselect-ui form-control form-control-sm">
                         </select>             
                     </label>
                 </div>
             </div>`
         );
        result.forEach(function (elem) {
            $('#leadGroupSelector').append(`<option value="${elem.id}" ${elem.id == 3 ? 'selected' : ''} >${elem.name}</option>`)
        })
    }

    function generationRegionManagerSelector(result) {
        $('#sectorRegionManagerFilter').html(
            `<div class="filter filter__reg-manager">
                 <div class="filtter__title">
                     <span class='title'>Региональный менеджер</span>
                 </div>
                 <div class="filter__content">
                     <label>
                        <select name="regionManager_id[]" id="leadRegionManagerSelector" class="multiselect-ui form-control form-control-sm" multiple="multiple">
                        </select>           
                     </label>
                 </div>
             </div>`
         );

        result.forEach(function (elem) {
            $('#leadRegionManagerSelector').append(`<option value="${elem.id}">${elem.name}</option>`)
        })
    }

    function generationSalonSelector(result) {
        $('#sectorSalonFilter').html(
            `<div class="filter filter__salon">
                 <div class="filtter__title">
                     <span class='title'>Салон</span>
                 </div>
                 <div class="filter__content">
                     <label>
                        <select name="salon_id[]" id="leadSalon" class="multiselect-ui form-control form-control-sm" multiple="multiple">
                        </select>          
                     </label>
                 </div>
             </div>`
         );

        result.forEach(function (elem) {
            $('#leadSalon').append(`<option value="${elem.id}">${elem.name}</option>`)
        })
    }

    function generationManagerSelector(result) {
        $('#sectorManagerFilter').html(
            `<div class="filter filter__manager">
                 <div class="filtter__title">
                     <span class='title'>Менеджер</span>
                 </div>
                 <div class="filter__content">
                     <label>
                        <select name="manager_id[]" id="leadManagerSelector" class="multiselect-ui form-control form-control-sm" multiple="multiple">
                        </select>          
                     </label>
                 </div>
             </div>`
         );

        result.forEach(function (elem) {
            $('#leadManagerSelector').append(`<option value="${elem.id}">${elem.name}</option>`)
        })
    }
});



