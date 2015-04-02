$.mask.masks.numero3 = {mask: '999'};
$.mask.masks.numero6 = {mask: '999999'};

$(function () {
    $("#tabs").tabs({
        disabled: [1, 2]
    });
});
jQuery(function ($) {
    $('input[type="text"]').setMask();
});

$(function () {
    $('.encounterCard').sortable({
        connectWith: ".connectedSortable",
        placeholder: "ui-state-highlight",
        forcePlaceholderSize: true
    }).disableSelection();
});

function ativarSortable() {
    $('.encounterCard').each(function () {
        var card = $(this);
        card.sortable({
            connectWith: ".connectedSortable",
            placeholder: "ui-state-highlight",
            forcePlaceholderSize: true
        }).disableSelection();
    });

}

var xpThreshold = '';
var multiplierIndex = '';
var host = window.location.hostname;
$('#grupo2').hide();
getDataAventura();
$("#msg").hide();

//aplica a mascara para os campos dinâmicos
function aplicaMask() {
    $('input[alt]').each(function () {
        var input = $(this);
        input.setMask(input.data('mask'));
    });
}

$('body').on('focus', '.datepicker', function () {
    $(".datepicker").datepicker();
});


//*************************************
//FUNÇÕES PARA A ABA CONSULTA ENCONTROS
//*************************************

$("#btnLimparConsulta").click(function () {
    $('#tabResult > tbody:last').html('');
    $('#monsterName').val('');
    $('#crMin option[value=""]').attr('selected', 'selected');
    $('#crMax option[value=""]').attr('selected', 'selected');
    $('#type option[value=""]').attr('selected', 'selected');
    $('#alignment option[value=""]').attr('selected', 'selected');

});

$("#btnLimparEncontro").click(function () {
    $('#tabEncontro > tbody:last').html('');
    $('#totalEncontro').html('');
    $('#dificuldade').html('');
    $('#adjustedXP').html('');
    $('#tituloEncontro').val('');
    $('#tesouro').val('');
    $('#msg').hide();
});

$("#btnConsultar").click(function () {
    var callOptions = {
        "monsterName": $("#monsterName").val(),
        "crMin": $("#crMin").val(),
        "crMax": $("#crMax").val(),
        "type": $("#type").val(),
        "alignment": $("#alignment").val()
    };
    $.ajax({
        url: 'http://' + host + '/dndapps/monsters/search',
        type: 'GET',
        data: callOptions,
        async: true
    }).done(function (data, textStatus, request) {
        $('#tabResult > tbody:last').html('');
        var img_class = 'marked';
        $.each(eval(data.replace(/[\r\n]/, "")), function (key, monster) {
            $('#tabResult > tbody:last').append('<tr id="monster' + monster['Monsters']['id'] + '"><td><a onclick="toggleFavorite(\'' + monster['Monsters']['id'] + '\')"><img height="24px" id="favorite' + monster['Monsters']['id'] + '" class="' + img_class + '" src="/dndapps/img/pentagram_off.png"></td><td><a onclick="adicionarAoEncontro(\'' + monster['Monsters']['id'] + '\')"><img src="/dndapps/img/plus24px.png"></a></td><td id="tdMonster">' + monster['Monsters']['name'] + '</td><td id="tdType">' + monster['MonsterTypes'][0]['dnd_type_id'] + '</td><td id="tdCr">' + monster['Monsters']['cr'] + '</td><td id="tdSize">' + monster['Monsters']['size'] + '</td><td id="tdAlignment">' + monster['Monsters']['alignment'] + '</td><td id="tdPage">' + monster['Monsters']['page'] + '</td></tr>');
        });
    });
});

function adicionarAoEncontro(monsterId) {
    var monsterData = document.getElementById('monster' + monsterId);
    var monster = $(monsterData).find('td#tdMonster').html();
    var cr = $(monsterData).find('td#tdCr').html();
    var page = $(monsterData).find('td#tdPage').html();
    $('#tabEncontro > tbody:last').append('<tr class="encounterMonster" id="' + monsterId + '"><td> <a onclick="excluirDoEncontro(\'' + monsterId + '\')"><img src="/dndapps/img/minus24px.png"></a></td><td>' + monster + '</td><td><input type="text" id="qtde' + monsterId + '" class="quantidade" alt="numero3" size="3" maxlength="3"></td><td id="tdCr">' + cr + '</td><td>' + page + '</td><td id="xp' + monsterId + '" class="xpEncounter"></td></tr>');
    aplicaMask();
}

function excluirDoEncontro(monsterId) {
    $('#totalEncontro').html('');
    $('#dificuldade').html('');
    $('#adjustedXP').html('');
    $('table#tabEncontro tr#' + monsterId).remove();
    $('.quantidade').change();
}

function toggleFavorite(MonsterId) {
    console.log(MonsterId);
    if ($('#favorite' + MonsterId).hasClass('unmarked')) {
        console.log('if');
        $('#favorite' + MonsterId).removeClass('unmarked');
        $('#favorite' + MonsterId).attr('src', '/dndapps/img/pentagram_off.png')
        console.log($('#favorite' + MonsterId).attr('src'));
    } else {
        console.log('else');
        console.log($('#favorite' + MonsterId).attr('src'));
        $('#favorite' + MonsterId).attr('src', '/dndapps/img/pentagram_on.png')
        $('#favorite' + MonsterId).addClass('unmarked');
    }
}

$('#tabEncontro').on('change', '.quantidade', function () {
    var trId = $(this).closest('tr').attr('id');
    var total = 0;
    var monsterData = document.getElementById(trId);
    var cr = $(monsterData).find('td#tdCr').html();
    var qtde = Number($('#qtde' + trId).val());
    var xp = Number(dnd_xp[cr]);
    $('#xp' + trId).html(qtde * xp);

    var values = $('.xpEncounter').map(function () {
        return $(this).html();
    }).get();

    $.each(values, function (index, valor) {
        total += Number(valor);
    });
    $('#totalEncontro').html(total);

    if (xpThreshold != '') {
        var totalMonsters = 0;
        var listMonsters = $('.quantidade').map(function () {
            return $(this).val();
        }).get();
        $.each(listMonsters, function (index, qtde) {
            totalMonsters += Number(qtde);
        });


        var multiplier = '';
        if (totalMonsters <= 1) {
            multiplier = '1';
        } else if (totalMonsters <= 2) {
            multiplier = '2';
        } else if (totalMonsters <= 6) {
            multiplier = '3-6';
        } else if (totalMonsters <= 10) {
            multiplier = '7-10';
        } else if (totalMonsters <= 14) {
            multiplier = '11-14';
        } else {
            multiplier = '15+';
        }

        if (total < xpThreshold['easy'][multiplier]) {
            $('#dificuldade').html('<font color="FF66FF"><strong id="diff">Too Easy</strong></font>');
        } else if (total < xpThreshold['medium'][multiplier]) {
            $('#dificuldade').html('<font color="CC99FF"><strong id="diff">Easy</strong></font>');
        } else if (total < xpThreshold['hard'][multiplier]) {
            $('#dificuldade').html('<font color="3300FF"><strong id="diff">Medium</strong></font>');
        } else if (total < xpThreshold['deadly'][multiplier]) {
            $('#dificuldade').html('<font color="FF9900"><strong id="diff">Hard</strong></font>');
        } else {
            $('#dificuldade').html('<font color="FF0000"><strong id="diff">Deadly</strong></font>');
        }
        $('#adjustedXP').html(total * Number(multiplierIndex[multiplier]));
    }
});

$("#btnSalvarEncontro").click(function () {

    var idAventura = $('#data').val();
    if (idAventura == '') {
        alert('Data da Aventura deve ser selecionada');
    } else {
        var monsterId = $('.encounterMonster').map(function () {
            return $(this).attr('id');
        }).get();
        if (monsterId == '') {
            alert('Encontro está vazio');
        } else {
            var monsterQtde = $('.quantidade').map(function () {
                return $(this).val();
            }).get();
            var callOptions = {
                "monsterId": monsterId,
                "monsterQtde": monsterQtde,
                "idAventura": idAventura,
                "totalEncontro": $('#totalEncontro').html(),
                "adjustedXP": $('#adjustedXP').html(),
                "difficulty": $('#diff').html(),
                "tituloEncontro": $('#tituloEncontro').val(),
                "tesouro": $('#tesouro').val()
            };
            $.ajax({
                url: 'http://' + host + '/dndapps/encounters/saveEncounter',
                type: 'GET',
                data: callOptions,
                async: true
            }).done(function (data, textStatus, request) {
                $("#msg").show();
                console.log(data);

            });
        }
    }
});

//*******************************
//Funções para montar a aba Grupo
//*******************************
function getDataAventura() {

    $.ajax({
        dataType: "json",
        url: 'http://' + host + '/dndapps/encounters/getAventureDates',
        type: 'GET',
        async: true
    }).done(function (data, textStatus, request) {
        var options = '<option value="">SELECIONE</option>';
        $.each(data, function (key, dataAventura) {
            options += '<option value="' + key + '">' + dataAventura + '</option>';
        });
        $('#data').html(options);

        //teste
        $('#data option:last-child').attr('selected', 'selected');
        $('#data').change();
        $('#t2').click();
        //teste
    });
}

$("#data").change(function () {
    if ($("#data").val() == "") {
        $('#grupoHeader').html('<thead></thead><tbody></tbody>');
        $('#xpThreshhold').html('<thead></thead><tbody></tbody>');
        $('#adventurers').html('<thead></thead><tbody></tbody>');
        $("#tabs").tabs("option", "disabled", [1, 2]);
    } else {
        $("#tabs").tabs("option", "disabled", []);
        var callOptions = {
            "idAventura": $("#data").val()
        };
        $.ajax({
            dataType: "json",
            url: 'http://' + host + '/dndapps/encounters/getAdventurers',
            type: 'GET',
            data: callOptions,
            async: true
        }).done(function (data, textStatus, request) {
            multiplierIndex = data['multiplierIndex'];
            xpThreshold = data['xpMultiplier'];
            $('#saveButtom').html('');
            $('#grupoHeader').html('<tr><td width="300px"><h4><strong>Adjusted XP per Adventuring Day: </strong></h4></td><td id="XPDay">' + data['adjustedXpDay'] + '</td></tr>');
            $('#easyXP').html(data['xpThreshold']['easy']);
            $('#mediumXP').html(data['xpThreshold']['medium']);
            $('#hardXP').html(data['xpThreshold']['hard']);
            $('#deadlyXP').html(data['xpThreshold']['deadly']);
            $('#xpThreshhold').html('<thead></thead><tbody></tbody>');
            $('#xpThreshhold > thead:last').append('<tr><td width="150"></td><th id="oneCreat">1 (x' + data['multiplierIndex']['1'] + ') </th><th id="twoCreat">2 (x' + data['multiplierIndex']['2'] + ')</th><th id="threeCreat">3-6 (x' + data['multiplierIndex']['3-6'] + ')</th><th id="sevenCreat">7-10 (x' + data['multiplierIndex']['7-10'] + ') </th><th id="elevenCreat">11-14 (x' + data['multiplierIndex']['11-14'] + ') </th><th id="fifteenCreat"> 15+ (x' + data['multiplierIndex']['15+'] + ')</th></tr>');
            $('#xpThreshhold > tbody:last').append('<tr><td>Easy</td><td>' + data['xpMultiplier']['easy']['1'] + '</td><td>' + data['xpMultiplier']['easy']['2'] + '</td><td>' + data['xpMultiplier']['easy']['3-6'] + '</td><td>' + data['xpMultiplier']['easy']['7-10'] + '</td><td>' + data['xpMultiplier']['easy']['11-14'] + '</td><td>' + data['xpMultiplier']['easy']['15+'] + '</td></tr>');
            $('#xpThreshhold > tbody:last').append('<tr><td>Medium</td><td>' + data['xpMultiplier']['medium']['1'] + '</td><td>' + data['xpMultiplier']['medium']['2'] + '</td><td>' + data['xpMultiplier']['medium']['3-6'] + '</td><td>' + data['xpMultiplier']['medium']['7-10'] + '</td><td>' + data['xpMultiplier']['medium']['11-14'] + '</td><td>' + data['xpMultiplier']['medium']['15+'] + '</td></tr>');
            $('#xpThreshhold > tbody:last').append('<tr><td>Hard</td><td>' + data['xpMultiplier']['hard']['1'] + '</td><td>' + data['xpMultiplier']['hard']['2'] + '</td><td>' + data['xpMultiplier']['hard']['3-6'] + '</td><td>' + data['xpMultiplier']['hard']['7-10'] + '</td><td>' + data['xpMultiplier']['hard']['11-14'] + '</td><td>' + data['xpMultiplier']['hard']['15+'] + '</td></tr>');
            $('#xpThreshhold > tbody:last').append('<tr><td>Deadly</td><td>' + data['xpMultiplier']['deadly']['1'] + '</td><td>' + data['xpMultiplier']['deadly']['2'] + '</td><td>' + data['xpMultiplier']['deadly']['3-6'] + '</td><td>' + data['xpMultiplier']['deadly']['7-10'] + '</td><td>' + data['xpMultiplier']['deadly']['11-14'] + '</td><td>' + data['xpMultiplier']['deadly']['15+'] + '</td></tr>');
            $('#adventurers').html('<thead></thead><tbody></tbody>');
            $('#adventurers > thead:last').append('<tr><th width="40px"></th><th>Name</th><th>Race</th><th>Class</th><th>Player</th><th>Level Inicial</th><th>XP Final</th></tr>');
            $.each(data['adventurers'], function (key, adventurer) {
                if (adventurer['AdventurersPerAdventure']['xp_final'] == null) {
                    xp_final = '<input type="text" id="xp_final' + adventurer['Adventurers']['id'] + '" class="xp_final" alt="numero6" size="8" maxlength="6">';
                    $('#saveButtom').html('<div align="center" class="pure-control-group"><button class="pure-button pure-button-primary" type="button" id="btnAtualizarXP">Atualizar XP</button></div>');
                } else {
                    xp_final = adventurer['AdventurersPerAdventure']['xp_final'];
                }
                $('#adventurers > tbody:last').append('<tr id="aventureiro' + adventurer['Adventurers']['id'] + '" class="saveonclick"><td id="img' + adventurer['Adventurers']['id'] + '"><a onclick="toggleAusencia(\'' + adventurer['Adventurers']['id'] + '\')"><img height="38px" src="/dndapps/img/green-dragon.png"></a></td><td>' + adventurer['Adventurers']['name'] + '</td><td>' + adventurer['Adventurers']['race'] + '</td><td>' + dnd_classes[adventurer['Adventurers']['class']] + '</td><td>' + adventurer['Adventurers']['player'] + '</td><td>' + adventurer['AdventurersPerAdventure']['lvl_inicial'] + '</td><td>' + xp_final + '</td></tr>');
                if (adventurer['AdventurersPerAdventure']['ausente'] == '1') {
                    $('#aventureiro' + adventurer['Adventurers']['id']).addClass('ausencia');
                    $('#img' + adventurer['Adventurers']['id']).html('<a onclick="toggleAusencia(\'' + adventurer['Adventurers']['id'] + '\')"><img height="38px" src="/dndapps/img/pink-paw-print.png"></a>');
                }
                aplicaMask();

            });
        });
    }
});
$("#btnNovaAventura").click(function () {
    $('#grupoHeader').html('<thead></thead><tbody></tbody>');
    $('#xpThreshhold').html('<thead></thead><tbody></tbody>');
    $('#adventurers').html('<thead></thead><tbody></tbody>');
    $('#grupo1').hide();
    $('#grupo2').show();
    $.ajax({
        dataType: "json",
        url: 'http://' + host + '/dndapps/characters/getListCharacters',
        type: 'GET',
        async: true
    }).done(function (data, textStatus, request) {
        $('#adventurers > thead:last').append('<tr><th width="40px"></th><th>Name</th><th>Race</th><th>Class</th><th>Player</th><th>Level</th></tr>');
        $.each(data, function (key, adventurer) {
            var select = '<select class="selectLvl id="lvl' + adventurer['Adventurers']['id'] + '">';
            for (var count = 1; count <= 20; count++) {
                select += '<option value="' + count + '">' + count + '</option>';
            }
            select += '</select>';
            $('#adventurers > tbody:last').append('<tr id="aventureiro' + adventurer['Adventurers']['id'] + '"><td id="img' + adventurer['Adventurers']['id'] + '"><a onclick="toggleAusencia(\'' + adventurer['Adventurers']['id'] + '\')"><img height="38px" src="/dndapps/img/green-dragon.png"></a></td><td>' + adventurer['Adventurers']['name'] + '</td><td>' + adventurer['Adventurers']['race'] + '</td><td>' + dnd_classes[adventurer['Adventurers']['class']] + '</td><td>' + adventurer['Adventurers']['player'] + '</td><td>' + select + '</td></tr>');
        });
        $('#saveButtom').html('<div align="center"><button class="pure-button button-warning" type="button" id="btnVoltar">Voltar</button> <button class="pure-button pure-button-primary" type="button" id="btnSalvarAventura">Salvar Aventura</button></div>');
    });
});
$('body').on('click', '#btnVoltar', function () {
    $('#adventurers').html('');
    $('#saveButtom').html('');
    $('#grupo1').show();
    $('#grupo2').hide();
    $('#data').change();
});
$('body').on('click', '#btnSalvarAventura', function () {
    var values = $('.selectLvl').map(function () {
        return $(this).val();
    }).get();
    var ausencia = $('.ausencia').map(function () {
        return $(this).attr('id');
    }).get();
    var callOptions = {
        "dataAventura": $('#dataAventura').val(),
        "level": values,
        "ausencia": ausencia
    };
    $.ajax({
        dataType: "json",
        url: 'http://' + host + '/dndapps/characters/salvarGrupo',
        type: 'GET',
        data: callOptions,
        async: true
    }).done(function (data, textStatus, request) {

        if (data == 'ok') {
            $('#btnVoltar').click();
            getDataAventura();
        } else {
            alert('deu pau!');
        }
    });
});
$('body').on('click', '#btnAtualizarXP', function () {
    var values = $('.xp_final').map(function () {
        return $(this).val();
    }).get();
    console.log($('#data').val());
    var callOptions = {
        "idAventura": $('#data').val(),
        "xp": values
    };
    $.ajax({
        dataType: "json",
        url: 'http://' + host + '/dndapps/characters/atualizarXP',
        type: 'GET',
        data: callOptions,
        async: true
    }).done(function (data, textStatus, request) {
        console.log(data);

        if (data == 'ok') {
            console.log('ade');
        } else {
            alert('deu pau!');
        }
    });
});
function toggleAusencia(adventurerId) {
    if ($('#aventureiro' + adventurerId).hasClass('ausencia')) {
        $('#aventureiro' + adventurerId).removeClass('ausencia');
        $('#img' + adventurerId).html('<a onclick="toggleAusencia(\'' + adventurerId + '\')"><img height="38px" src="/dndapps/img/green-dragon.png"></a>');
        var ausente = '0';
    } else {
        $('#aventureiro' + adventurerId).addClass('ausencia');
        $('#img' + adventurerId).html('<a onclick="toggleAusencia(\'' + adventurerId + '\')"><img height="38px" src="/dndapps/img/pink-paw-print.png"></a>');
        var ausente = '1';
    }

    if ($('#aventureiro' + adventurerId).hasClass('saveonclick')) {
        var callOptions = {
            "idAventura": $('#data').val(),
            "adventurer": adventurerId,
            "ausente": ausente
        };
        $.ajax({
            dataType: "json",
            url: 'http://' + host + '/dndapps/encounters/atualizaPresenca',
            type: 'GET',
            data: callOptions,
            async: true
        }).done(function (data, textStatus, request) {
            if (data == 'ok') {
                $("#data").change();
            } else {
                alert('deu pau!');
            }
        });
    }
}

$("#t2").click(function () {
    if ($('#data').val() == '') {
        alert('Data da Aventura deve ser selecionada');
    }
});

//Funções para montar a aba Aventura

$("#t3").click(function () {
    $('#listaEncontros > tbody:last').html('');
    if ($('#data').val() == '') {
        alert('Data da Aventura deve ser selecionada');
    } else {
    }
    var callOptions = {
        "idAventura": $('#data').val()
    };
    $.ajax({
        dataType: "json",
        url: 'http://' + host + '/dndapps/encounters/getEncounters',
        type: 'GET',
        data: callOptions,
        async: true
    }).done(function (data, textStatus, request) {
//        console.log(data);
        var contador = 0;
        var encounter_card_row = '';

        $.each(data, function (key, encontro) {
            var diff_class = 'diff_unknown';
            if (encontro['Encounters']['difficulty'] === 'Deadly') {
                diff_class = 'diff_deadly';
            } else if (encontro['Encounters']['difficulty'] === 'Hard') {
                diff_class = 'diff_hard';
            } else if (encontro['Encounters']['difficulty'] === 'Medium') {
                diff_class = 'diff_medium';
            } else if (encontro['Encounters']['difficulty'] === 'Easy') {
                diff_class = 'diff_easy';
            } else if (encontro['Encounters']['difficulty'] === 'Too Easy') {
                diff_class = 'diff_easy';
            }

            encounter_card = '<td class="encounterCard connectedSortable ui-state-default" id="pos' + contador + '"><table id="encounter' + encontro['Encounters']['id'] + '" ><thead><tr><td class="' + diff_class + '" colspan="3">' + encontro['Encounters']['title'] + '</td></tr></thead><tbody><tr><td width="36px"><img width="40px" src="/dndapps/img/Overstuffed_Treasure_Chest-icon.png"></td><td>' + encontro['Encounters']['treasure'] + '</td></tr></tbody></table></td>';
            if (contador++ % 2 === 0) {
                encounter_card_row += encounter_card;
            } else {
                encounter_card_row += encounter_card;
                $('#listaEncontros > tbody:last').append('<tr>' + encounter_card_row + '</tr>');
                encounter_card_row = '';
            }
            var monsters = '';
            $.each(encontro['EncountersMonsters'], function (key, monster) {
                $.ajax({
                    dataType: "json",
                    url: 'http://' + host + '/dndapps/encounters/getMonster',
                    type: 'GET',
                    data: {"monsterId": monster['dnd_monsters_id']},
                    async: true
                }).done(function (monsterData, textStatus, request) {
                    monsters = '<tr><td width="36px"><img width="36px" src="/dndapps/img/dragon-bullet-small2.png"></td><td><b>' + monsterData['Monsters']['name'] + ' (' + monster['quantidade'] + ')</b>, pag ' + monsterData['Monsters']['page'] + '</td></tr>';
                    $('#encounter' + encontro['Encounters']['id'] + ' > tbody').prepend('<tr>' + monsters + '</tr>');

                });
            });
        });
        if (contador++ % 2 != 0) {
            $('#listaEncontros > tbody:last').append('<tr>' + encounter_card_row + '</tr>');
        }
        ativarSortable();
    });
});