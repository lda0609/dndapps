//menu
$(function () {
    $("#menu").menu({
        items: "> :not(.ui-widget-header)"
    });
});

$('.menu-button').click(function () {
    $(".forms").hide();
    var item = $(this).html();
    item = item.replace(/\s/g, "-");
    item = item.replace(/,/g, "");
    console.log(item);
    $('#' + item).show();
});


//Funções para selecionar resistencia, vulnerabilidade e imunidade
$(".btnVulnerability").click(function () {
    dmgType = $(this).attr('alt');
    deleteIcon = '<img class="clickable btnRemoveDmgType vulnerability" align="right" width="24px" src="/dndapps/img/deleteIcon.png" alt="' + dmgType + '">';
    $("#vulnerabilities").append('<li class="ui-widget-content">' + dmgType + deleteIcon);
    $("#liType" + dmgType).hide();

});

$(".btnImmunity").click(function () {
    dmgType = $(this).attr('alt');
    deleteIcon = '<img class="clickable btnRemoveDmgType immunity" align="right" width="24px" src="/dndapps/img/deleteIcon.png">';
    $("#immunities").append('<li class="ui-widget-content">' + dmgType + deleteIcon);
    $("#liType" + dmgType).hide();

});

$(".btnResistance").click(function () {
    dmgType = $(this).attr('alt');
    deleteIcon = '<img class="clickable btnRemoveDmgType resistance" align="right" width="24px" src="/dndapps/img/deleteIcon.png">';
    $("#resistances").append('<li class="ui-widget-content">' + dmgType + deleteIcon);
    $("#liType" + dmgType).hide();
});

$('ul').on('click', '.btnRemoveDmgType', function () {
    dmgType = $(this).attr('alt');
    console.log(dmgType);
    $(this).closest('li').remove();
    $("#liType" + dmgType).show();
});

