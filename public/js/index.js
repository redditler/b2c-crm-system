$(document).ready(function () {
    $('.type-dropdown').on('click', function(){
        $(this).toggleClass('active');
    })
    
    $('.user-menu__name-wrapper').on('click', function(){
        $('.navbar__toggle-menu').toggleClass('active');
    });

    $(document).mouseup(function (e){ 
        var div = $(".navbar__toggle-menu"); 
        if (!div.is(e.target) && div.has(e.target).length === 0) { 
            div.removeClass('active'); 
        }
    });

    // Добавление пользовательского календаря 
    $('.datepicker').datepicker({
        monthNames: ['Январь', 'Февраль', 'Март', 'Апрель',
            'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь',
            'Октябрь', 'Ноябрь', 'Декабрь'],
        dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
        firstDay: 1,
        showAnim: "drop",
        dateFormat: 'yy-mm-dd',
    });

    // Добавление пользовательского select 
    $('.multiselect-ui').multiselect({
        includeSelectAllOption: true,
        enableFiltering: true,
        defaultChecked: true,
        nonSelectedText: 'Выбрать',
        selectAllText: 'Все',
        allSelectedText: 'Все'
    });

    showActivePage();

    showAddFilters();

    addTreeveiwAnimation();
});


const showActivePage = () =>{
    let currentPage = location.pathname.slice(1);
    if(currentPage.split('/').length > 1){
        currentPage = currentPage.split('/')[0];
    }
    let pages = {
        'leads': 'living_tape',
        'store_lead': 'living_tape',
        'leadCanceledShow': 'rejected',
        'leadsPromo': 'stocks',
        'contact' : 'customers',
        'statistics_new': 'statistics',
    }

    $(`#${pages[currentPage]}`).addClass('active');
}

const addTreeveiwAnimation = () =>{
    let a = document.querySelectorAll('.treeview .sidebar__link');
    if(a){
        [].forEach.call(a, function (el) {
            el.addEventListener('click', function (e) {
                    this.parentNode.classList.toggle('show');
            })
        });
        
        let b = document.querySelector('.sidebar__btn');

        $(b).on('click', () => {
            document.querySelector('.sidebar__btn').classList.toggle('active');
            document.querySelector('.sidebar').classList.toggle('hide');
        });
    }
}

const showAddFilters = () => {
    $('#addFiltersShow').on('click', () => {
        $('#addFiltersShow').toggleClass('active');
        $('.filters__footer').toggleClass('active');
    })
}

// Панель выбора цвета в создани заметки
// $('.current-color-event').on('click', function () {
    //Value number color
    // let eventColors = ['#01b6cf', '#3f4f67', '#213054', '#1f949e', '#006068', '#d1905f', '#f47a7f', '#ff939c', '#f9b988'];

    // let colorList = $('.color-list-for-event');
    // let currentColor = $('.current-color-event');
    // let currentValueOfColorList = $('.color-list-for-event').css('display');

    // Open/Close list cahnge colors
    // if(currentValueOfColorList == 'none'){
    //     colorList.show(200);
    //     colorList.css('display', 'flex');
    // }else{
    //     colorList.hide(200);
    // }
//     // Add event on click at color
//     for(let i = 0; i < eventColors.length; i++){
//         $('.color-event-' + (i+1)).on('click', function () {
//             colorList.hide(200);
//             currentColor.css('background-color', eventColors[i]);
//             $('#color_event').val(eventColors[i]);
//         });
//     }
// })

