const leadXlsFilter = () => {
      $('#leadDateToXLS').val( $('#leadDateTo').val() );
      $('#leadDateFromXls').val( $('#leadDateFrom').val() );

      $('#leadGroupSelectorXls').val( [$('#leadGroupSelector').val()] );
      $('#leadRegionManagerSelectorXls').val( [$('#leadRegionManagerSelector').val()] );
      $('#leadSalonXls').val( [$('#leadSalon').val()] );
      $('#leadManagerSelectorXls').val( [$('#leadManagerSelector').val()] );

      $('#leadStatusXls').val( JSON.parse( $('#funnel').attr('data-status')) );
  }