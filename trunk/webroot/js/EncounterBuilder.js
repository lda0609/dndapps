//$.mask.masks.numero3 = {mask: '999'};
var xpThreshold = '';

$("#btnLimparConsulta").click(function () {
    $('#tabResult > tbody:last').html('');
});

$("#btnLimparEncontro").click(function () {
    $('#tabEncontro > tbody:last').html('');
    $('#totalEncontro').html('');
    $('#dificuldade').html('');
});

$("#btnConsultar").click(function () {
    var host = window.location.hostname;
    var callOptions = {
        "monsterName": $("#monsterName").val(),
        "crMin": $("#crMin").val(),
        "crMax": $("#crMax").val(),
        "type": $("#type").val(),
        "alignment": $("#alignment").val(),
    };
    $.ajax({
        url: 'http://' + host + '/dndapps/monsters/search',
        type: 'POST',
        data: callOptions,
        async: true
    }).done(function (data, textStatus, request) {
        $('#tabResult > tbody:last').html('');
        $.each(eval(data.replace(/[\r\n]/, "")), function (key, monster) {
            $('#tabResult > tbody:last').append('<tr id="monster' + monster['Monsters']['id'] + '"><td><a onclick="adicionarAoEncontro(\'' + monster['Monsters']['id'] + '\')"><img src="/dndapps/img/plus24px.png"></a></td><td id="tdMonster">' + monster['Monsters']['name'] + '</td><td id="tdType">' + monster['MonsterTypes'][0]['dnd_type_id'] + '</td><td id="tdCr">' + monster['Monsters']['cr'] + '</td><td id="tdSize">' + monster['Monsters']['size'] + '</td><td id="tdAlignment">' + monster['Monsters']['alignment'] + '</td><td id="tdPage">' + monster['Monsters']['page'] + '</td></tr>');
        });
    });
});

function adicionarAoEncontro(monsterId) {
    var monsterData = document.getElementById('monster' + monsterId);
    var monster = $(monsterData).find('td#tdMonster').html();
    var cr = $(monsterData).find('td#tdCr').html();
    var page = $(monsterData).find('td#tdPage').html();
    $('#tabEncontro > tbody:last').append('<tr id="' + monsterId + '"><td> <a onclick="excluirDoEncontro(\'' + monsterId + '\')"><img src="/dndapps/img/minus24px.png"></a></td><td>' + monster + '</td><td><input id="qtde' + monsterId + '" class="quantidade" alt="numero3" size="3" maxlength="3"></td><td id="tdCr">' + cr + '</td><td>' + page + '</td><td id="xp' + monsterId + '" class="xpEncounter"></td></tr>');
}

function excluirDoEncontro(monsterId) {
    console.log(monsterId);
    $('#totalEncontro').html('');
    $('table#tabEncontro tr#' + monsterId).remove();
    $('.quantidade').change();
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

        console.log(multiplier);
        console.log(total);
        if (total < xpThreshold['easy'][multiplier]) {
            $('#dificuldade').html('<font color="FF66FF"><strong>Too Easy</strong></font>');
        } else if (total < xpThreshold['medium'][multiplier]) {
            $('#dificuldade').html('<font color="CC99FF"><strong>Easy</strong></font>');
        } else if (total < xpThreshold['hard'][multiplier]) {
            $('#dificuldade').html('<font color="3300FF"><strong>Medium</strong></font>');
        } else if (total < xpThreshold['deadly'][multiplier]) {
            $('#dificuldade').html('<font color="FF9900"><strong>Hard</strong></font>');
        } else {
            $('#dificuldade').html('<font color="FF0000"><strong>Deadly</strong></font>');
        }

    }
});


//Funções para montar a aba Grupo

$("#btnBuscarGrupo").click(function () {
    var host = window.location.hostname;
    var callOptions = {
        "dataAventura": $("#data").val(),
    };
    console.log(callOptions);

    $.ajax({
        dataType: "json",
        url: 'http://' + host + '/dndapps/encounters/getAdventurers',
        type: 'POST',
        data: callOptions,
        async: true
    }).done(function (data, textStatus, request) {
//        console.log(data);
        xpThreshold = data['xpMultiplier'];
        $('#XPDay').html(data['adjustedXpDay']);
        $('#easyXP').html(data['xpThreshold']['easy']);
        $('#mediumXP').html(data['xpThreshold']['medium']);
        $('#hardXP').html(data['xpThreshold']['hard']);
        $('#deadlyXP').html(data['xpThreshold']['deadly']);

        $('#xpThreshhold').html('<thead></thead><tbody></tbody>');
        $('#xpThreshhold > thead:last').append('<tr><td width="150"></td><th id="oneCreat">1 (x' + data['multiplierIndex']['1'] + ') </th><th id="twoCreat">2 (x' + data['multiplierIndex']['2'] + ')</th><th id="threeCreat">3-6 (x' + data['multiplierIndex']['3'] + ')</th><th id="sevenCreat">7-10 (x' + data['multiplierIndex']['7'] + ') </th><th id="elevenCreat">11-14 (x' + data['multiplierIndex']['11'] + ') </th><th id="fifteenCreat"> 15+ (x' + data['multiplierIndex']['15'] + ')</th></tr>');
        $('#xpThreshhold > tbody:last').append('<tr><td>Easy</td><td>' + data['xpMultiplier']['easy']['1'] + '</td><td>' + data['xpMultiplier']['easy']['2'] + '</td><td>' + data['xpMultiplier']['easy']['3-6'] + '</td><td>' + data['xpMultiplier']['easy']['7-10'] + '</td><td>' + data['xpMultiplier']['easy']['11-14'] + '</td><td>' + data['xpMultiplier']['easy']['15+'] + '</td></tr>');
        $('#xpThreshhold > tbody:last').append('<tr><td>Medium</td><td>' + data['xpMultiplier']['medium']['1'] + '</td><td>' + data['xpMultiplier']['medium']['2'] + '</td><td>' + data['xpMultiplier']['medium']['3-6'] + '</td><td>' + data['xpMultiplier']['medium']['7-10'] + '</td><td>' + data['xpMultiplier']['medium']['11-14'] + '</td><td>' + data['xpMultiplier']['medium']['15+'] + '</td></tr>');
        $('#xpThreshhold > tbody:last').append('<tr><td>Hard</td><td>' + data['xpMultiplier']['hard']['1'] + '</td><td>' + data['xpMultiplier']['hard']['2'] + '</td><td>' + data['xpMultiplier']['hard']['3-6'] + '</td><td>' + data['xpMultiplier']['hard']['7-10'] + '</td><td>' + data['xpMultiplier']['hard']['11-14'] + '</td><td>' + data['xpMultiplier']['hard']['15+'] + '</td></tr>');
        $('#xpThreshhold > tbody:last').append('<tr><td>Deadly</td><td>' + data['xpMultiplier']['deadly']['1'] + '</td><td>' + data['xpMultiplier']['deadly']['2'] + '</td><td>' + data['xpMultiplier']['deadly']['3-6'] + '</td><td>' + data['xpMultiplier']['deadly']['7-10'] + '</td><td>' + data['xpMultiplier']['deadly']['11-14'] + '</td><td>' + data['xpMultiplier']['deadly']['15+'] + '</td></tr>');

        $('#adventurers').html('<thead></thead><tbody></tbody>');
        $('#adventurers > thead:last').append('<tr><th>Name</th><th>Race</th><th>Class</th><th>Player</th><th>Level</th></tr>');

        $.each(data['adventurers'], function (key, adventurer) {
            $('#adventurers > tbody:last').append('<tr><td>' + adventurer['Adventurers']['name'] + '</td><td>' + adventurer['Adventurers']['race'] + '</td><td>' + dnd_classes[adventurer['Adventurers']['class']] + '</td><td>' + adventurer['Adventurers']['player'] + '</td><td>' + adventurer['AdventurersPerAdventure']['lvl_inicial'] + '</td></tr>');
        });
    });
});
