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
//    var character_header;
//    var atributes;
//    var outros_dados;
    var cont = 1;
    console.log(data);
    $.each(data, function (key, characterData) {
        if (characterData.AdventurersPerAdventure.xp_final == null) {
            characterData.AdventurersPerAdventure.xp_final = '';
        }
        var rows = '';
        var html_skills = '';
        rows += '<tr><td colspan="6" class="name_PC"><font class="card-name">' + characterData.Adventurers.name + '</font><br><font class="card-details">' + characterData.Adventurers.background + ' ' + dnd_classes[characterData.Adventurers.class] + ' ' + characterData.AdventurersPerAdventure.lvl_inicial + ', ' + dnd_alignment_players[characterData.Adventurers.alignment] + '</font><hr class="hr-paper-flip"></td></tr>';
        rows += '<tr><td class="label">AC</td><td>' + characterData.CharacterProgression.ac + '</td><td class="label">SPD</td><td>' + characterData.CharacterProgression.speed + '</td><td class="label">Init</td><td>' + characterData.CharacterProgression.initiative + '</td></tr>';
        rows += '<tr><td class="label">STR</td><td>' + characterData.CharacterProgression.strength + '</td><td class="label">DEX</td><td>' + characterData.CharacterProgression.dextery + '</td><td class="label">CON</td><td>' + characterData.CharacterProgression.constitution + '</td></tr>';
        rows += '<tr><td class="label">INT</td><td>' + characterData.CharacterProgression.intelligence + '</td><td class="label">WIS</td><td>' + characterData.CharacterProgression.wisdom + '</td><td class="label">CHA</td><td>' + characterData.CharacterProgression.charisma + '</td></tr>';

        html_skills = '<tr><td colspan="6"><font class="card-name">Skills </font><hr class="hr-paper-flip"></td></tr><tr>';
        var cont_skill = 1;
        var passive_perception = 10;
        var hasPerception = false;
        $.each(characterData['AdventurersSkills'], function (key, skill) {
            html_skills += '<td class="label"> ' + dnd_skills[skill['AdventurersSkills']['dnd_skills_id']] + '</td><td> ' + skill['AdventurersSkills']['modifier'] + '</td>';
            if (cont_skill++ % 2 === 0) {
                html_skills += '</tr><tr>';
            }
            if (skill['AdventurersSkills']['dnd_skills_id'] === '13') {
                hasPerception = true;
                passive_perception = passive_perception + Number(skill['AdventurersSkills']['modifier']);
            }
        });
        if (!hasPerception) {
            bonus_wisdom = Math.floor((Number(characterData.CharacterProgression.wisdom) - 10) / 2);
            passive_perception += bonus_wisdom;
        }

        html_skills += '<tr><td colspan="3"><strong>Passive Perception</strong></td><td>' + passive_perception + '</td></tr>';
        character_card = '<table>' + rows + html_skills +'</table>';

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
        console.log(data);
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
    $('#personagensGravados option[value=""]').attr('selected', 'selected');
    $('.field').val('');
    $('.skill').val('');
    $("#btnGravar").show();
    $("#btnAlterar").hide();
    $("#btnLevelUp").hide();


});