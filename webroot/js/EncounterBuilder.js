$.mask.masks.numero3 = {mask: '999'};
$.mask.masks.numero6 = {mask: '999999'};
var players = '';
$(function () {
    $("#tabs").tabs({
        disabled: [1, 2, 3]
    });
});
jQuery(function ($) {
    $('input[type="text"]').setMask();
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
//FUNÇÕES PARA A ABA CRIAR ENCONTROS
//*************************************
$("#t1").click(function () {
    $('.tracker-side-frame').hide();
    $('.td_footer').hide();
});

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
    $('#information').val('');
    $('#msg').hide();
});

$("#btnConsultar").click(function () {
    var callOptions = {
        "monsterName": $("#monsterName").val(),
        "crMin": $("#crMin").val(),
        "crMax": $("#crMax").val(),
        "type": $("#type").val(),
        "alignment": $("#alignment").val(),
        "favoritos": '0'
    };
    $.ajax({
        url: 'http://' + host + '/dndapps/monsters/search',
        type: 'GET',
        data: callOptions,
        async: true
    }).done(function (data, textStatus, request) {
        showResult(data);
    });
});

$("#btnConsultarFavoritos").click(function () {
    var callOptions = {
        "favoritos": '1'
    };
    $.ajax({
        url: 'http://' + host + '/dndapps/monsters/search',
        type: 'GET',
        data: callOptions,
        async: true
    }).done(function (data, textStatus, request) {
        showResult(data);
    });
});

function showResult(data) {
    $('#tabResult > tbody:last').html('');
    $.each(eval(data.replace(/[\r\n]/, "")), function (key, monster) {
        var img_class = '';
        var img_src = '/dndapps/img/pentagram_off.png';
        if (monster['MonsterFavorites'] == '1') {
            img_class = 'favorite';
            img_src = '/dndapps/img/pentagram_on.png';
        }
        $('#tabResult > tbody:last').append('<tr id="monster' + monster['Monsters']['id'] + '"><td><a onclick="toggleFavorite(\'' + monster['Monsters']['id'] + '\')"><img height="24px" id="favorite' + monster['Monsters']['id'] + '" class="' + img_class + ' clickable" src="' + img_src + '"></td><td><a onclick="adicionarAoEncontro(\'' + monster['Monsters']['id'] + '\')"><img class="clickable" src="/dndapps/img/plus24px.png"></a></td><td id="tdMonster">' + monster['Monsters']['name'] + '</td><td id="tdType">' + monster['MonsterTypes'][0]['dnd_type_id'] + '</td><td id="tdCr">' + monster['Monsters']['cr'] + '</td><td id="tdSize">' + monster['Monsters']['size'] + '</td><td id="tdHP">' + monster['Monsters']['hp'] + '</td><td id="tdAlignment">' + monster['Monsters']['alignment'] + '</td><td id="tdPage">' + monster['Monsters']['page'] + '</td></tr>');
    });
}


function adicionarAoEncontro(monsterId) {
    var monsterData = document.getElementById('monster' + monsterId);
    var monster = $(monsterData).find('td#tdMonster').html();
    var cr = $(monsterData).find('td#tdCr').html();
    var page = $(monsterData).find('td#tdPage').html();
    var hp = $(monsterData).find('td#tdHP').html();
    $('#tabEncontro > tbody:last').append('<tr class="encounterMonster" id="' + monsterId + '"><td> <a onclick="excluirDoEncontro(\'' + monsterId + '\')"><img class="clickable" src="/dndapps/img/minus24px.png"></a></td><td>' + monster + '</td><td><input type="text" id="qtde' + monsterId + '" class="quantidade" alt="numero3" size="3" maxlength="3"></td><td id="tdCr">' + cr + '</td><td>' + hp + '</td><td id="xp' + monsterId + '" class="xpEncounter"></td><td>' + page + '</td></tr>');
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
    if ($('#favorite' + MonsterId).hasClass('favorite')) {
        $.ajax({
            url: 'http://' + host + '/dndapps/encounters/removeFavorite',
            type: 'GET',
            data: {"monsterId": MonsterId},
            async: true
        }).done(function (data, textStatus, request) {
            $('#favorite' + MonsterId).removeClass('favorite');
            $('#favorite' + MonsterId).attr('src', '/dndapps/img/pentagram_off.png');
        });
    } else {
        $.ajax({
            url: 'http://' + host + '/dndapps/encounters/addFavorite',
            type: 'GET',
            data: {"monsterId": MonsterId},
            async: true
        }).done(function (data, textStatus, request) {
            $('#favorite' + MonsterId).attr('src', '/dndapps/img/pentagram_on.png');
            $('#favorite' + MonsterId).addClass('favorite');
        });
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

    if (xpThreshold !== '') {
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
    if (idAventura === '') {
        alert('Data da Aventura deve ser selecionada');
    } else {
        var monsterId = $('.encounterMonster').map(function () {
            return $(this).attr('id');
        }).get();
        if (monsterId === '') {
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
                "tesouro": $('#tesouro').val(),
                "information": $('#information').val()
            };
            $.ajax({
                url: 'http://' + host + '/dndapps/encounters/saveEncounter',
                type: 'GET',
                data: callOptions,
                async: true
            }).done(function (data, textStatus, request) {
                console.log(data);
                $("#msg").show();
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
        url: 'http://' + host + '/dndapps/encounters/getAdventureDates',
        type: 'GET',
        async: true
    }).done(function (data, textStatus, request) {
        var options = '<option value="">SELECIONE</option>';
        $.each(data, function (key, dataAventura) {
            options += '<option value="' + key + '">' + dataAventura + '</option>';
        });
        $('#data').html(options);
        //teste
//        $('#data option:last-child').attr('selected', 'selected');
//        $('#data').change();
//        $('#t4').click();
        //teste
    });
}

$('#data').change(function () {
    mostraInfoGrupo(0);
});

function mostraInfoGrupo(atualizaAventura) {
    if ($("#data").val() === "") {
        $('#XPDay').html('');
        $('#xpThreshhold').html('<thead></thead><tbody></tbody>');
        $('#adventurers').html('<thead></thead><tbody></tbody>');
        $("#tabs").tabs("option", "disabled", [1, 2]);
    } else {
        $("#tabs").tabs("option", "disabled", []);
        var callOptions = {
            "idAventura": $("#data").val(),
            "atualizaAventura": atualizaAventura
        };
        $.ajax({
            dataType: "json",
            url: 'http://' + host + '/dndapps/encounters/getAdventurers',
            type: 'GET',
            data: callOptions,
            async: true
        }).done(function (data, textStatus, request) {
            players = data['adventurers'];
            var countAdventurer = 0;
            multiplierIndex = data['multiplierIndex'];
            xpThreshold = data['xpMultiplier'];
            $('#saveButtom').html('');
            $('#XPDay').html(data['adjustedXpDay']);
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
                if (adventurer['AdventurersPerAdventure']['xp_final'] === null) {
                    xp_final = '<input type="text" id="xp_final' + adventurer['Adventurers']['id'] + '" class="xp_final" alt="numero6" size="8" maxlength="6">';
                    $('#saveButtom').html('<div align="center" class="pure-control-group"><button class="pure-button pure-button-primary" type="button" id="btnAtualizarXP">Atualizar XP</button></div>');
                } else {
                    xp_final = adventurer['AdventurersPerAdventure']['xp_final'];
                }
                $('#adventurers > tbody:last').append('<tr id="aventureiro' + adventurer['Adventurers']['id'] + '" class="saveonclick"><td id="img' + adventurer['Adventurers']['id'] + '"><a onclick="toggleAusencia(\'' + adventurer['Adventurers']['id'] + '\')"><img height="38px" src="/dndapps/img/green-dragon.png" class="clickable"></a></td><td>' + adventurer['Adventurers']['name'] + '</td><td>' + adventurer['Adventurers']['race'] + '</td><td>' + dnd_classes[adventurer['Adventurers']['class']] + '</td><td>' + adventurer['Adventurers']['player'] + '</td><td>' + adventurer['AdventurersPerAdventure']['lvl_inicial'] + '</td><td>' + xp_final + '</td></tr>');

                if (adventurer['AdventurersPerAdventure']['ausente'] === '1') {
                    $('#aventureiro' + adventurer['Adventurers']['id']).addClass('ausencia');
                    $('#img' + adventurer['Adventurers']['id']).html('<a onclick="toggleAusencia(\'' + adventurer['Adventurers']['id'] + '\')"><img height="38px" src="/dndapps/img/pink-paw-print.png" class="clickable"></a>');
                } else {
                    countAdventurer++;
                }
                aplicaMask();
            });
            $.data(document.body, "countAdventurer", countAdventurer);
        });
    }
}


$("#btnNovaAventura").click(function () {
    $('#XPDay').html('');
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
        console.log(data);
        $('#adventurers > thead:last').append('<tr><th width="40px"></th><th>Name</th><th>Race</th><th>Class</th><th>Player</th><th>Level</th></tr>');
        $.each(data, function (key, adventurer) {
            var select = '<select class="selectLvl id="lvl' + adventurer['Adventurers']['id'] + '">';
            for (var count = 1; count <= 20; count++) {
                if (count === adventurer['Adventurers']['lvl_inicial']) {
                    select += '<option selected="selected" value="' + count + '">' + count + '</option>';
                } else {
                    select += '<option value="' + count + '">' + count + '</option>';
                }
            }
            select += '</select>';
            $('#adventurers > tbody:last').append('<tr id="aventureiro' + adventurer['Adventurers']['id'] + '"><td id="img' + adventurer['Adventurers']['id'] + '"><a onclick="toggleAusencia(\'' + adventurer['Adventurers']['id'] + '\')"><img height="38px" src="/dndapps/img/green-dragon.png" class="clickable"></a></td><td>' + adventurer['Adventurers']['name'] + '</td><td>' + adventurer['Adventurers']['race'] + '</td><td>' + dnd_classes[adventurer['Adventurers']['class']] + '</td><td>' + adventurer['Adventurers']['player'] + '</td><td>' + select + '</td></tr>');
        });
        $('#saveButtom').html('<div align="center"><button class="pure-button button-warning" type="button" id="btnVoltar">Voltar</button> <button class="pure-button pure-button-primary" type="button" id="btnSalvarAventura">Salvar Aventura</button></div>');
    });
});

$('body').on('click', '#btnVoltar', function () {
    $('#adventurers').html('');
    $('#saveButtom').html('');
    $('#grupo1').show();
    $('#grupo2').hide();
    mostraInfoGrupo(0);
});

$('body').on('click', '#btnSalvarAventura', function () {
    var values = $('.selectLvl').map(function () {
        return $(this).val();
    }).get();
    var ausencia = $('.ausencia').map(function () {
        return $(this).attr('id');
    }).get();
    var aventureirosID = $('#adventurers tr').map(function () {
        return $(this).attr('id');
    }).get();

    console.log(aventureirosID);
    var callOptions = {
        "dataAventura": $('#dataAventura').val(),
        "level": values,
        "ausencia": ausencia,
        "aventureirosID": aventureirosID
    };
    console.log(callOptions);
    $.ajax({
        dataType: "json",
        url: 'http://' + host + '/dndapps/characters/salvarGrupo',
        type: 'GET',
        data: callOptions,
        async: true
    }).done(function (data, textStatus, request) {

        if (data === 'ok') {
            $('#btnVoltar').click();
            getDataAventura();
        } else {
            alert('deu pau!');
        }
    });
});

$(function () {
    $("#confirmaXP").dialog({
        autoOpen: false,
        resizable: false,
        height: 200,
        modal: true,
        buttons: {
            "Atualizar": function () {
                AtualizacaoXPconfirmada();
                $(this).dialog("close");
            },
            "Cancelar": function () {
                $(this).dialog("close");
            }
        }
    });
});


$('body').on('click', '#btnAtualizarXP', function () {
    $("#confirmaXP").dialog("open");


});

function AtualizacaoXPconfirmada() {
    var values = $('.xp_final').map(function () {
        return $(this).val();
    }).get();
    var aventureirosID = $('#adventurers tr').map(function () {
        return $(this).attr('id');
    }).get();

    var callOptions = {
        "idAventura": $('#data').val(),
        "xp": values,
        "aventureirosID": aventureirosID,
    };
    $.ajax({
        dataType: "json",
        url: 'http://' + host + '/dndapps/characters/atualizarXP',
        type: 'GET',
        data: callOptions,
        async: true
    }).done(function (data, textStatus, request) {
        console.log(data);
        if (data !== 'ok') {
            alert('deu pau!');
        } else {
            $("#data").change();
        }
    });
}


function toggleAusencia(adventurerId) {
    if ($('#aventureiro' + adventurerId).hasClass('ausencia')) {
        $('#aventureiro' + adventurerId).removeClass('ausencia');
        $('#img' + adventurerId).html('<a onclick="toggleAusencia(\'' + adventurerId + '\')"><img height="38px" src="/dndapps/img/green-dragon.png" class="clickable"></a>');
        var ausente = '0';
    } else {
        $('#aventureiro' + adventurerId).addClass('ausencia');
        $('#img' + adventurerId).html('<a onclick="toggleAusencia(\'' + adventurerId + '\')"><img height="38px" src="/dndapps/img/pink-paw-print.png" class="clickable"></a>');
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
            if (data === 'ok') {
                mostraInfoGrupo(1);
            } else {
                alert('deu pau!');
            }
        });
    }
}

$("#t2").click(function () {
    $('.tracker-side-frame').hide();
    $('.td_footer').hide();
    if ($('#data').val() === '') {
        alert('Data da Aventura deve ser selecionada');
    }
});

//*************************************
//FUNÇÕES PARA A ABA CONSULTAR AVENTURA
//*************************************

$("#t3").click(function () {
    $('.tracker-side-frame').hide();
    $('.td_footer').hide();
    $('#listaEncontros > tbody:last').html('');
    if ($('#data').val() === '') {
        alert('Data da Aventura deve ser selecionada');
    } else {
        var callOptions = {
            "idAventura": $('#data').val()
        };
        $.ajax({
            dataType: "json",
            url: 'http://' + host + '/dndapps/encounters/getAllEncounters',
            type: 'GET',
            data: callOptions,
            async: true
        }).done(function (data, textStatus, request) {
            var contador = 0;
            var encounter_card_row = '';
            var xp = 0;
            var adjustedXp = 0;
            console.log(data);
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

                var img_excluir = '<td class="' + diff_class + '" width="28px"><a onclick="excluirEncontro(\'' + encontro['Encounters']['id'] + '\')"><img width="28px" src="/dndapps/img/document_delete.png" title="Exclui encontro" class="clickable"></a></td>';
                var img_turntracker = '<td class="' + diff_class + '" width="28px"><p align="center"><a onclick="enviarEncontroParaTracker(\'' + encontro['Encounters']['id'] + '\')"><img width="28px" src="/dndapps/img/attack4.png" title="Carrega encontro no Turn Tracker" class="clickable"></a></p></td>';
                var tbody = '<tbody><tr><td width="36px"><img width="40px" src="/dndapps/img/Overstuffed_Treasure_Chest-icon.png"></td><td colspan="3">' + encontro['Encounters']['treasure'] + '</td></tr><tr><td width="36px"><img width="40px" src="/dndapps/img/xp-icon.png"></td><td colspan="3">' + encontro['Encounters']['xp'] + '/' + encontro['Encounters']['adjusted_xp'] + '</td></tr><tr><td width="36px"><img width="40px" src="/dndapps/img/information.png"></td><td colspan="3">' + encontro['Encounters']['information'] + '</td></tr></tbody>'

                encounter_card = '<td class="encounterCard connectedSortable ui-state-default" id="pos' + contador + '"><table id="encounter' + encontro['Encounters']['id'] + '" ><thead><tr>' + img_turntracker + '<td class="' + diff_class + '" colspan="2">' + encontro['Encounters']['title'] + '</td>' + img_excluir + '</tr></thead>' + tbody + '</table></td>';
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
                        url: 'http://' + host + '/dndapps/monsters/getMonster',
                        type: 'GET',
                        data: {"monsterId": monster['dnd_monsters_id']},
                        async: true
                    }).done(function (monsterData, textStatus, request) {
                        monsters = '<tr><td width="36px"><img width="36px" src="/dndapps/img/dragon-bullet-small2.png"></td><td colspan="3"><b>' + monsterData['Monsters']['name'] + ' (' + monster['quantidade'] + ')</b>, pag ' + monsterData['Monsters']['page'] + '</td></tr>';
                        $('#encounter' + encontro['Encounters']['id'] + ' > tbody').prepend('<tr>' + monsters + '</tr>');

                    });
                });
                //calcula totais
                xp += Number(encontro['Encounters']['xp']);
                adjustedXp += Number(encontro['Encounters']['adjusted_xp']);
            });
            if (contador++ % 2 !== 0) {
                $('#listaEncontros > tbody:last').append('<tr>' + encounter_card_row + '</tr>');
            }
            var countAdventurer = Number($.data(document.body, "countAdventurer"));

            $('#AventuraXP').html(xp + '/' + parseInt(xp / countAdventurer));
            $('#AventuraAdjustedXP').html(adjustedXp);
        });
    }
});

$("#t4").click(function () {
    $('.tracker-side-frame').show();
    $('.td_footer').show();

    if ($('#data').val() === '') {
        alert('Data da Aventura deve ser selecionada');
    }
});

function excluirEncontro(encounterId) {
    $.data(document.body, "encounterId", encounterId);
    $("#dialog").dialog("open");
}

$(function () {
    $("#dialog").dialog({
        autoOpen: false,
        resizable: false,
        height: 180,
        modal: true,
        buttons: {
            "Certeza": function () {
                excluirEncontroConfirmado();
                $(this).dialog("close");
            },
            "Não": function () {
                $(this).dialog("close");
            }
        }
    });
});

function excluirEncontroConfirmado() {
    $.ajax({
        dataType: "json",
        url: 'http://' + host + '/dndapps/encounters/deleteEncounter',
        type: 'GET',
        data: {"encounterId": $.data(document.body, "encounterId")},
        async: true
    }).done(function (data, textStatus, request) {
        $.data(document.body, "confirmaExclusao", "");
        $('#listaEncontros').html('<tbody></tbody>');
        $("#t3").click();
    });
}

function enviarEncontroParaTracker(encounterId) {
    console.log(encounterId);
    $.ajax({
        dataType: "json",
        url: 'http://' + host + '/dndapps/encounters/getEncounter',
        type: 'GET',
        data: {"encounterId": encounterId},
        async: true
    }).done(function (data, textStatus, request) {
        loadEncounter(data);
        $("#t4").click();
    });
}
