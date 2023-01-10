<!--Combined Ledger Autocomplite search-->
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT
        2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous">
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
    var availableTags = [];
    $.ajax({
        method: "GET",
        url: "/ledger_autocomplete_search",
        success: function(response) {
            startAutoComplete(response);
        }
    });

    function startAutoComplete(availableTags) {
        $("#customer_ledger_search").autocomplete({
            source: availableTags
        });
    }
</script>
<!--End Combined Ledger Autocomplite search-->



<!--category Autocomplite search-->
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT
        2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous">
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
    var availableTags = [];
    $.ajax({
        method: "GET",
        url: "/category_autocomplete_search",
        success: function(response) {
            startAutoComplete(response);
        }
    });

    function startAutoComplete(availableTags) {
        $("#category_search").autocomplete({
            source: availableTags
        });
    }
</script>
<!--End category Autocomplite search-->

<!--Stock Autocomplite search-->
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT
        2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous">
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
    var availableTags = [];
    $.ajax({
        method: "GET",
        url: "/stock_autocomplete_search",
        success: function(response) {
            startAutoComplete(response);
        }
    });

    function startAutoComplete(availableTags) {
        $("#shop_stock_search").autocomplete({
            source: availableTags
        });
    }
</script>
<!--End stock Autocomplite search-->


<!--Godown Autocomplite search-->
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT
        2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous">
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
    var availableTags = [];
    $.ajax({
        method: "GET",
        url: "/godownstock_autocomplete_search",
        success: function(response) {
            startAutoComplete(response);
        }
    });

    function startAutoComplete(availableTags) {
        $("#godown_stock_search").autocomplete({
            source: availableTags
        });
    }
</script>
<!--End Godown Autocomplite search-->


<!--Contact Autocomplite search-->
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT
        2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous">
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
    var availableTags = [];
    $.ajax({
        method: "GET",
        url: "/contact_autocomplete_search",
        success: function(response) {
            startAutoComplete(response);
        }
    });

    function startAutoComplete(availableTags) {
        $("#contact_search").autocomplete({
            source: availableTags
        });
    }
</script>
<!--End Contact Autocomplite search-->


<!--Return Purchase Autocomplite search-->
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT
        2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous">
</script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="/resources/demos/style.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script>
    var availableTags = [];
    $.ajax({
        method: "GET",
        url: "/purchase_return_autocomplete_search",
        success: function(response) {
            startAutoComplete(response);
        }
    });

    function startAutoComplete(availableTags) {
        $("#purchase_return_search").autocomplete({
            source: availableTags
        });
    }
</script>
<!--End Return Purchase Autocomplite search-->