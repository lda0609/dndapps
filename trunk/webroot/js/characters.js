$("#msg_inclusao").hide();
$("#msg_lvlup").hide();
$("#btnAlterar").hide();
$("#btnLevelUp").hide();


var host = window.location.hostname;

$(function () {
    $("#tabs").tabs({
    });
});

getDataAventura();
getCharactersAllLevels();

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

        $("#t2").click(); //teste

    });
}

$('#data').change(function () {
    var callOptions = {
        "idAventura": $("#data").val(),
    };
    $.ajax({
        dataType: "json",
        url: 'http://' + host + '/dndapps/characters/getAdventurersFromDate',
        type: 'GET',
        data: callOptions,
        async: true
    }).done(function (data, textStatus, request) {
        showCharactersCards(data);
    });
});

function showCharactersCards(data) {
    $('#allCharacters').html('');
    var character_card;
    var character_header;
    var atributes;
    var outros_dados;
    var cont = 1;
    $.each(data, function (key, characterData) {
        if (characterData.AdventurersPerAdventure.xp_final == null) {
            characterData.AdventurersPerAdventure.xp_final = '';
        }
        character_header = '<table class="characterCard"><tr><td rowspan="2">' + characterData.Adventurers.name + '</td><td>' + dnd_classes[characterData.Adventurers.class] + ' ' + characterData.AdventurersPerAdventure.lvl_inicial + '</td><td>' + characterData.Adventurers.background + '</td><td>' + characterData.Adventurers.race + '</td></tr><tr><td>' + characterData.Adventurers.player + '</td><td>' + dnd_alignment_players[characterData.Adventurers.alignment] + '</td><td>' + characterData.AdventurersPerAdventure.xp_final + '</td></tr></table>';
        atributes = '<table><tr><td>Str: ' + characterData.CharacterProgression.strength + '</td><td>Dex: ' + characterData.CharacterProgression.dextery + '</td><td>Con: ' + characterData.CharacterProgression.constitution + '</td></tr><tr><td>Int: ' + characterData.CharacterProgression.intelligence + '</td><td>Wis: ' + characterData.CharacterProgression.wisdom + '</td><td>Cha: ' + characterData.CharacterProgression.charisma + '</td></tr></table>';
        outros_dados = '<table><tr><td>AC: ' + characterData.CharacterProgression.ac + '</td><td>HP: ' + characterData.CharacterProgression.hit_point_max + '</td><td>Init: ' + characterData.CharacterProgression.initiative + '</td><td>Speed: ' + characterData.CharacterProgression.speed + '</td></tr></table>';
        character_card = character_header + outros_dados + atributes;

        if (cont === 1) {
            cards_row = '<td>' + character_card + '</td>';
            cont = 2
        } else {
            cards_row = cards_row + '<td>' + character_card + '</td>';
            $('#allCharacters').append('<tr>' + cards_row + '</tr>');
            cont = 1
        }
    });
    if (cont === 2) {
        $('#allCharacters').append('<tr>' + cards_row + '</tr>');
    }
}

// Tab Inclusão de novo personagem

$('#btnGravar').click(function () {
    salvarPersonagem('saveCharacter');
});

$('#btnLevelUp').click(function () {
    salvarPersonagem('characterLevelUp');
});

$('#btnAlterar').click(function () {
    salvarPersonagem('editCharacter');
});

function salvarPersonagem(funcao) {
    var adventurer = {}
    adventurer['Adventurers'] = {}
    adventurer['CharacterProgression'] = {}
    adventurer['Adventurers']['name'] = $('#name').val();
    adventurer['Adventurers']['race'] = $('#race').val();
    adventurer['Adventurers']['class'] = $('#class').val();
    adventurer['Adventurers']['player'] = $('#player').val();
    adventurer['Adventurers']['background'] = $('#background').val();
    adventurer['Adventurers']['alignment'] = $('#alignment').val();

    adventurer['CharacterProgression']['lvl'] = $('#level').val();
    adventurer['CharacterProgression']['strength'] = $('#str').val();
    adventurer['CharacterProgression']['dextery'] = $('#dex').val();
    adventurer['CharacterProgression']['constitution'] = $('#con').val();
    adventurer['CharacterProgression']['intelligence'] = $('#int').val();
    adventurer['CharacterProgression']['wisdom'] = $('#wis').val();
    adventurer['CharacterProgression']['charisma'] = $('#cha').val();
    adventurer['CharacterProgression']['ac'] = $('#ac').val();
    adventurer['CharacterProgression']['initiative'] = $('#init').val();
    adventurer['CharacterProgression']['speed'] = $('#speed').val();
    adventurer['CharacterProgression']['hit_point_max'] = $('#hp').val();

    var values = $('.skill').map(function () {
        if ($(this).val() !== '') {
            return [{'dnd_skills_id': $(this).attr("id"), 'modifier': $(this).val()}];
        }
    }).get();
    adventurer['AdventurersSkills'] = values;
    var callOptions = {
        "adventurer": adventurer,
    };
    $.ajax({
        dataType: "json",
        url: 'http://' + host + '/dndapps/characters/' + funcao,
        type: 'GET',
        data: callOptions,
        async: true
    }).done(function (data, textStatus, request) {
        console.log(data);
        if (data === 'ok') {
            getCharactersAllLevels();
            $("#msg_inclusao").show();
            $('#btnLimpar').click();
        } else {
            alert('Erro na gravação dos dados');
        }
    });
}


function getCharactersAllLevels() {
    var options = '<option value="">Selecione para editar</option>';
    $.ajax({
        dataType: "json",
        url: 'http://' + host + '/dndapps/characters/getCharactersAllLevels',
        type: 'GET',
        async: true
    }).done(function (data, textStatus, request) {
        $.each(data, function (key, adventurer) {
            options += '<option value="' + adventurer['CharacterProgressionId'] + '">' + adventurer['name'] + ' - lvl ' + adventurer['lvl'] + '</option>';
        });
        $('#personagensGravados').html(options);
    });
}

$('#personagensGravados').change(function () {
    var callOptions = {
        "CharacterProgressionId": $('#personagensGravados').val(),
    };
    if ($('#personagensGravados').val() !== '') {
        $.ajax({
            dataType: "json",
            url: 'http://' + host + '/dndapps/characters/getCharacterDetails',
            type: 'GET',
            data: callOptions,
            async: true
        }).done(function (data, textStatus, request) {
            console.log(data);
            $('#name').val(data['Adventurers'].name);
            $('#race').val(data['Adventurers'].race);
            $('#class').val(data['Adventurers'].class);
            $('#player').val(data['Adventurers'].player);
            $('#background').val(data['Adventurers'].background);
            $('#alignment').val(data['Adventurers'].alignment);

            $('#level').val(data['CharacterProgression'].lvl);
            $('#str').val(data['CharacterProgression'].strength);
            $('#dex').val(data['CharacterProgression'].dextery);
            $('#con').val(data['CharacterProgression'].constitution);
            $('#int').val(data['CharacterProgression'].intelligence);
            $('#wis').val(data['CharacterProgression'].wisdom);
            $('#cha').val(data['CharacterProgression'].charisma);
            $('#ac').val(data['CharacterProgression'].ac);
            $('#init').val(data['CharacterProgression'].initiative);
            $('#speed').val(data['CharacterProgression'].speed);
            $('#hp').val(data['CharacterProgression'].hit_point_max);

            $('.skill').val('');
            $.each(data['AdventurersSkills'], function (key, skill) {
                $('#' + skill.dnd_skills_id).val(skill.modifier);
            });


            $("#btnGravar").hide();
            $("#btnAlterar").show();
            $("#btnLevelUp").show();
        });
    } else {
        $("#btnGravar").show();
        $("#btnAlterar").hide();
        $("#btnLevelUp").hide();
        $('#btnLimpar').click();
    }
});
$('#btnLimpar').click(function () {
    $('.attribute_field option[value="6"]').attr('selected', 'selected');
    $('#class option[value="Barbarian"]').attr('selected', 'selected');
    $('#level option[value="1"]').attr('selected', 'selected');
    $('#alignment option[value="1"]').attr('selected', 'selected');
    $('.field').val('');
    $('.skill').val('');


});