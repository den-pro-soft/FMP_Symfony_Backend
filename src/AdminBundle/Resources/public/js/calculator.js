
$("#price_value").change(function(){
    var v = $("#price_value").val() * 62343;
    $("#url_value").val( "https://findmyprofession.com/custom-checkout/"+v );
});
