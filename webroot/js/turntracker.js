$(".hpModifier").attr('disabled', true);
$("#divTurnTracker").hide();

var allFighters = [];
var combatRound = 1;
function loadPlayers() {
    if (!$("#imgLoadPlayers").hasClass('button-disabled')) {
        $("#divTurnTracker").show();
        $("#imgLoadPlayers").addClass('button-disabled');
        $("#imgStartCombat").removeClass('button-disabled');
        configPlayers();
        turnTracker();
    }
}

function loadEncounter(encounter) {
    $("#divTurnTracker").show();
    $.each(encounter['monsters'], function (key, monster) {
        temp = {}
        temp['conditions'] = [];
        temp['id'] = 'm' + key;
        temp['name'] = monster['name'];
        temp['HPMax'] = monster['hp'];
        temp['HPTemp'] = 0;
        temp['HPAtual'] = temp['HPMax'];
        temp['iniciativa'] = 0;
        allFighters.push(temp);
    });
    $("#divInformation").html(encounter['information']);
    $("#imgStartCombat").removeClass('button-disabled');
    turnTracker();
}

function nextTurn() {
    if (!$("#imgNextTurn").hasClass('button-disabled')) {
        var nextRow = $('.currentTurn').closest('tr').next('tr');
        $('.currentTurn').removeClass('currentTurn');
        if (nextRow.length !== 0) {
            $(nextRow).addClass('currentTurn');
        } else {
            $("#tableTracker tbody > tr:first").addClass('currentTurn');
        }
        id = $('.currentTurn').attr("id");
        fighter = getFighterById(id);
        if ($('.currentTurn').hasClass('firstTurn')) {
            $('#combat-log').append('<br><font class="log-title">ROUND ' + combatRound++ + ' - FIGHT!</font><br>');
        }
        load_side_frame(fighter, 'td_left_size');
        $('#combat-log').append('<font class="log-title"><i class="fa fa-hourglass-start"></i> ' + fighter.name + ' Turn</font><br>');
        combat_log_anchor();
    }
}

function combat_log_anchor() {
    var out = document.getElementById("combat-log");
    var isScrolledToBottom = (out.scrollHeight - out.clientHeight) <= out.scrollTop + 60;
    if (isScrolledToBottom) {
        out.scrollTop = out.scrollHeight - out.clientHeight;
    }
}

function limparTracker() {
    allFighters = [];
    $('#tableTracker > tbody').html('');
    $("#imgLoadPlayers").removeClass('button-disabled')
    $("#imgNextTurn").addClass('button-disabled');
    $("#imgLimparTracker").addClass('button-disabled');
    $("#imgStartCombat").addClass('button-disabled');
    $("#toggleLockHPMod").removeClass('unlocked');
    $("#toggleLockHPMod").addClass('locked');
    $("#toggleLockHPMod").html('<i class="fa fa-lock"></i>');
    $(".initValue").attr('disabled', false);
    $("#toggleLockInit").removeClass('locked');
    $("#toggleLockInit").addClass('unlocked');
    $("#toggleLockInit").html('<i class="fa fa-unlock-alt"></i>');
    $(".hpModifier").attr('disabled', true);
    $('#td_left_size').html('');
    $('#td_right_size').html('');
    $('#combat-log').html('');
    $("#combat-log-frame").hide();
    combatTurn = 0;
}

function configPlayers() {

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
        $.each(data, function (key, player) {
            console.log(data)
            if (player['AdventurersPerAdventure']['ausente'] === '0') {
                temp = {};
                temp['details'] = {};
                temp['conditions'] = [];
                temp['id'] = 'p' + player['Adventurers']['id'];
                temp['name'] = player['Adventurers']['name'];
                temp['class'] = dnd_classes[player['Adventurers']['class']];
                temp['alignment'] = dnd_alignment_players[player['Adventurers']['alignment']];
                temp['background'] = player['Adventurers']['background'];
                temp['HPMax'] = player['CharacterProgression']['hit_point_max'];
                temp['HPTemp'] = 0;
                temp['HPAtual'] = temp['HPMax'];
                temp['iniciativa'] = 0;
                temp['details']['ac'] = player['CharacterProgression']['ac'];
                temp['details']['speed'] = player['CharacterProgression']['speed'];
                temp['details']['initiative'] = player['CharacterProgression']['initiative'];
                temp['details']['str'] = player['CharacterProgression']['strength'];
                temp['details']['dex'] = player['CharacterProgression']['dextery'];
                temp['details']['con'] = player['CharacterProgression']['constitution'];
                temp['details']['int'] = player['CharacterProgression']['intelligence'];
                temp['details']['wis'] = player['CharacterProgression']['wisdom'];
                temp['details']['cha'] = player['CharacterProgression']['charisma'];
                temp['AdventurersSkills'] = player['AdventurersSkills'];
                allFighters.push(temp);
            }
        });
        turnTracker();
    });
}

function turnTracker() {
    var selectedCharacterId = $('.selectedCharacter').attr('id');
    var currentTurnId = $('.currentTurn').attr('id');

    $('#tableTracker > tbody').html('');
    $.each(allFighters, function (key, values) {
        var corHPAtual = '';
        var HPTemp = '';
        var hp_stat = '<td></td>';
        var className = 'name_PC';
        if (values['id'].substring(0, 1) === 'm') {
            className = 'name_NPC';
        }
        if (values['HPAtual'] < values['HPMax']) {
            corHPAtual = 'red';
        }
        if (values['HPTemp'] > 0) {
            HPTemp = '<font color="blue"> (' + values['HPTemp'] + ')';
        }
        if (values['HPAtual'] <= 0) {
            hp_stat = '<td width="20px" class="selectable"><img width="20px" src="/dndapps/img/skull.png"></td>';
        } else if (values['HPAtual'] <= values['HPMax'] / 2) {
            hp_stat = '<td width="20px" class="selectable"><img width="20px" src="/dndapps/img/blood.png"></td>';
        }

        var hp = '<font color="' + corHPAtual + '">' + values['HPAtual'] + '</font>/' + values['HPMax'] + HPTemp;
        var init = '<td><input class="initValue" type="number" min="-10" max="40" value="' + values['iniciativa'] + '"></input></td>';
        var combat = '<td><input id="hpModifier' + values['id'] + '" type="number" class="hpModifier"></td>';

        $('#tableTracker > tbody:last').append('<tr id="' + values['id'] + '">' + init + '<td class="selectable"><font class="' + className + '">' + values['name'] + '</font></td>' + hp_stat + '<td width="300" class="selectable"><div class="progressbar" id="progressbar' + values['id'] + '"><div class="progress-label">' + hp + '</div></div></td>' + combat + '</tr> ');
        $(function () {
            $("#progressbar" + values['id']).progressbar({
                max: Number(values['HPMax']),
                value: Number(values['HPAtual'])
            });
        });
    });
    $(function () {
        $(".sortable").sortable({
            stop: function (event, ui) {
                ajustarLista();
            }
        });
    });
    checkLocks();

    selectedCharacter = document.getElementById(selectedCharacterId);
    currentTurn = document.getElementById(currentTurnId);
    $(selectedCharacter).addClass("selectedCharacter");
    $(currentTurn).addClass("currentTurn");
}
$("#btnDamage").click(function () {
    hpModifier(1);
    turnTracker();
});

$("#btnHeal").click(function () {
    hpModifier(2);
    turnTracker();
});

$("#btnHPTemp").click(function () {
    hpModifier(3);
    turnTracker();
});

//1: damage; 2: Heal; 3: HP temp
function hpModifier(modifier) {
    var hpMap = $('.hpModifier').map(function () {
        return [{"playerId": $(this).closest('tr').attr('id'), "hpMod": Number($(this).val())}];
    }).get();
    $.each(hpMap, function (key, value) {
        if (value['hpMod'] !== 0) {
            $.each(allFighters, function (key2, player) {
                if (value['playerId'] === player['id']) {
                    if (modifier === 1) {
                        $('#combat-log').append('<font class="log-name">' + allFighters[key].name + '</font> tinha ' + allFighters[key].HPAtual + ' de HP e levou <font class="log-damage">' + value['hpMod'] + '</font> de dano. <br>');
                        if (allFighters[key].HPTemp > 0) {
                            $('#combat-log').append('<font class="log-name">' + allFighters[key].name + '</font> tinha ' + allFighters[key].HPTemp + ' de HP temporário. <br>');
                            allFighters[key].HPTemp -= value['hpMod'];
                            if (allFighters[key].HPTemp < 0) {
                                allFighters[key].HPAtual = Number(allFighters[key].HPAtual);
                                allFighters[key].HPAtual += Number(allFighters[key].HPTemp);
                                allFighters[key].HPTemp = 0;
                            }
                        } else {
                            allFighters[key].HPAtual -= value['hpMod'];
                        }
                        $('#combat-log').append('<font class="log-name">' + allFighters[key].name + '</font> está com ' + allFighters[key].HPAtual + ' de HP. <br>');
                        if (allFighters[key].HPAtual < 0) {
                            allFighters[key].HPAtual = 0;
                            $('#combat-log').append('<font class="log-name">' + allFighters[key].name + '</font> morreu.<br>');
                        }
                    } else if (modifier === 2) {
                        $('#combat-log').append('<font class="log-name">' + allFighters[key].name + '</font> tinha ' + allFighters[key].HPAtual + ' HP e curou <font class="log-heal">' + value['hpMod'] + '</font> HP. <br>');
                        allFighters[key].HPAtual += value['hpMod'];
                        if (allFighters[key].HPAtual > allFighters[key].HPMax) {
                            allFighters[key].HPAtual = allFighters[key].HPMax;
                        }
                        $('#combat-log').append('<font class="log-name">' + allFighters[key].name + '</font> está com ' + allFighters[key].HPAtual + ' de HP. <br>');
                    } else if (modifier === 3) {
                        $('#combat-log').append('<font class="log-name">' + allFighters[key].name + '</font> tinha ' + allFighters[key].HPTemp + ' HP temporário e ganhou <font class="log-hp-temp">' + value['hpMod'] + '</font> HP temporário. <br>');
                        if (value['hpMod'] > allFighters[key].HPTemp) {
                            allFighters[key].HPTemp = value['hpMod'];
                        }
                        $('#combat-log').append('<font class="log-name">' + allFighters[key].name + '</font> está com ' + allFighters[key].HPTemp + ' HP temporário. <br>');
                    }
                }
                combat_log_anchor();
            });
        }
    });
}

$("#btnOdenar").click(function () {

    init = $('.initValue').map(function () {
        return [{"id": $(this).closest('tr').attr('id'), "iniciativa": Number($(this).val())}];
    }).get();

    $.each(init, function (key, playerInit) {
        $.each(allFighters, function (key2, player) {
            if (player.id == playerInit.id) {
                allFighters[key2].iniciativa = playerInit.iniciativa;
            }
        });
    });

    ordenarPorIniciativa();
    turnTracker();
});

function ordenarPorIniciativa() {
    var APlen = allFighters.length;
    var j = 0;
    var swapped = true;
    while (swapped) {
        swapped = false;
        j++;
        for (i = 0; i < APlen - j; i++) {
            if (allFighters[i]['iniciativa'] < allFighters[i + 1]['iniciativa']) {
                temp = allFighters[i];
                allFighters[i] = allFighters[i + 1];
                allFighters[i + 1] = temp;
                swapped = true;
            }
        }
    }
}

function ajustarLista() {
    var idMap = $('.hpModifier').map(function () {
        return [{"playerId": $(this).closest('tr').attr('id')}];
    }).get();
    temp = [];
    $.each(idMap, function (key, value) {
        $.each(allFighters, function (key2, value2) {
            if (value['playerId'] === value2['id']) {
                temp.push(value2);
            }
        });
    });
    allFighters = temp;
}

$("#toggleLockInit").click(function () {
    if ($(this).attr('class') === 'unlocked') {
        $(this).removeClass('unlocked');
        $(this).addClass('locked');
        $(this).html('<i class="fa fa-lock"></i>');
        $(".initValue").attr('disabled', true);
    } else {
        $(this).removeClass('locked');
        $(this).addClass('unlocked');
        $(this).html('<i class="fa fa-unlock-alt"></i>');
        $(".initValue").attr('disabled', false);
    }
});

$("#toggleLockHPMod").click(function () {
    if ($(this).attr('class') === 'unlocked') {
        $(this).removeClass('unlocked');
        $(this).addClass('locked');
        $(this).html('<i class="fa fa-lock"></i>');
        $(".hpModifier").attr('disabled', true);
    } else {
        $(this).removeClass('locked');
        $(this).addClass('unlocked');
        $(this).html('<i class="fa fa-unlock-alt"></i>');
        $(".hpModifier").attr('disabled', false);
    }
});

function checkLocks() {
    if ($("#toggleLockInit").attr('class') === 'locked') {
        $(".initValue").attr('disabled', true);
    }
    if ($("#toggleLockHPMod").attr('class') === 'locked') {
        $(".hpModifier").attr('disabled', true);
    }
}

function startCombat() {
    if (!$("#imgStartCombat").hasClass('button-disabled')) {

        $("#imgStartCombat").addClass('button-disabled');
        $("#imgNextTurn").removeClass('button-disabled');
        $("#imgLimparTracker").removeClass('button-disabled');
        $("#btnOdenar").click();
        $("#toggleLockInit").removeClass('unlocked');
        $("#toggleLockInit").addClass('locked');
        $("#toggleLockInit").html('<i class="fa fa-lock"></i>');
        $(".initValue").attr('disabled', true);
        $("#toggleLockHPMod").removeClass('locked');
        $("#toggleLockHPMod").addClass('unlocked');
        $("#toggleLockHPMod").html('<i class="fa fa-unlock-alt"></i>');
        $(".hpModifier").attr('disabled', false);
        $("#tableTracker tbody > tr").removeClass('currentTurn');
        $("#tableTracker tbody > tr:first").addClass('currentTurn');
        $("#tableTracker tbody > tr:first").addClass('firstTurn');
        var id = $('.currentTurn').attr("id");
        fighter = getFighterById(id);
        load_side_frame(fighter, 'td_left_size');
        $('#td_footer').html('<div id="combat-log-frame"><div id="combat-log" class="scrollit tracker-side-frame"></div><hr class="hr-blood"></div>');
        $('#combat-log').append('<font class="log-title">ROUND ' + combatRound++ + ' - FIGHT!</font><br>');
        $('#combat-log').append('<font class="log-title"><i class="fa fa-hourglass-start"></i> ' + fighter.name + ' Turn</font><br>');
        combat_log_anchor();
    }
}

$('body').on('click', '.selectable', function () {
    var selected = $(this).closest('tr').hasClass('selectedCharacter');
    $('#tableTracker tr').removeClass('selectedCharacter');
    if (!selected) {
        $(this).closest('tr').addClass('selectedCharacter');
    }
    var id = $(this).closest('tr').attr("id");
    fighter = getFighterById(id);
    console.log(fighter);
    load_side_frame(fighter, 'td_right_size');
});

function load_side_frame(fighter, side) {
    var rows = '';
    var html_skills = '';
    if (fighter['id'].substring(0, 1) === 'm') {
        rows += '<tr><td class="name_NPC card-name">' + fighter.name + '<hr class="hr-monster-card"></td></tr>';
    } else {
        //monta tabela com atributos
        rows += '<tr><td colspan="6" class="name_PC"><font class="card-name">' + fighter.name + '</font><br><font class="card-details">' + fighter.background + ' ' + fighter.class + ', ' + fighter.alignment + '</font><hr class="hr-paper-flip"></td></tr>';
        rows += '<tr><td class="label">AC</td><td>' + fighter.details.ac + '</td><td class="label">SPD</td><td>' + fighter.details.speed + '</td><td class="label">Init</td><td>' + fighter.details.initiative + '</td></tr>';
        rows += '<tr><td class="label">STR</td><td>' + fighter.details.str + '</td><td class="label">DEX</td><td>' + fighter.details.dex + '</td><td class="label">CON</td><td>' + fighter.details.con + '</td></tr>';
        rows += '<tr><td class="label">INT</td><td>' + fighter.details.int + '</td><td class="label">WIS</td><td>' + fighter.details.wis + '</td><td class="label">CHA</td><td>' + fighter.details.cha + '</td></tr>';

        //monta tabela com skills
        html_skills = '<font class="card-name">Skills </font><hr class="hr-paper-flip"><table class="' + side + ' tracker-side-frame"><tr>';
        var cont = 1;
        $.each(fighter['AdventurersSkills'], function (key, skill) {
            html_skills += '<td class="label"> ' + dnd_skills[skill['AdventurersSkills']['dnd_skills_id']] + '</td><td> ' + skill['AdventurersSkills']['modifier'] + '</td>';
            if (cont++ % 3 === 0) {
                html_skills += '</tr><tr>';
            }
        });
        html_skills += '</table>';

    }
    var content = '<table class=tracker-side-frame>' + rows + '</table>';

    //monta tabela com conditions
    conditions = ['Blinded', 'Charmed', 'Deafened', 'Exhaustion', 'Frightened', 'Grappled', 'Incapacitated', 'Invisible', 'Paralyzed', 'Petrified', 'Poisoned', 'Prone', 'Restrained', 'Stunned', 'Unconscious']
    html_conditions = '<font class="card-name">Conditions </font> <hr class="hr-paper-flip"><table class="' + side + ' tracker-side-frame"><tr>';
    var cont = 1;
    $.each(conditions, function (key, condition) {
        var hasCondition = '';
        if (fighter.conditions.indexOf(condition) > -1) {
            hasCondition = 'hasCondition';
        }
        html_conditions += '<td class="clickable conditions ' + hasCondition + '" name="' + condition + '"><i class="fa fa-plus-square"> ' + condition + '</i></td>';
        if (cont++ % 4 === 0) {
            html_conditions += '</tr><tr>';
        }
    });
    html_conditions += '</table>';

    //renderiza a tabela na tela
    $('#' + side).html('<table><tr><td>' + content + '</td></tr><tr><td>' + html_skills + '</td></tr><tr><td>' + html_conditions);
}

$('body').on('click', '.conditions', function () {
    var condition = $(this).attr('name');
    var side = $(this).closest('table').attr("class");
    if ($('.currentTurn').attr("id") === $('.selectedCharacter').attr("id")) {
        $('[name="' + condition + '"]').toggleClass('hasCondition');
    } else {
        $(this).toggleClass('hasCondition');
    }
    if ($(this).closest('table').hasClass("td_left_size")) {
        var id = $('.currentTurn').attr("id");
        fighter = getFighterById(id);
        if ($(this).hasClass('hasCondition')) {
            fighter.conditions.push(condition);
        } else {
            var index = fighter.conditions.indexOf(condition);
            if (index > -1) {
                fighter.conditions.splice(index, 1);
            }
        }
    } else {
        var id = $('.selectedCharacter').attr("id");
        fighter = getFighterById(id);
        if ($(this).hasClass('hasCondition')) {
            fighter.conditions.push(condition);
        } else {
            var index = fighter.conditions.indexOf(condition);
            if (index > -1) {
                fighter.conditions.splice(index, 1);
            }
        }
    }
});

function getFighterById(id) {
    $.each(allFighters, function (key, fighter) {
        if (fighter.id === id) {
            retorno = fighter;
            return false;
        }
    });
    return retorno;
}

//shortcuts

// define a handler
function doc_keyUp(e) {

    // this would test for whichever key is 40 and the ctrl key at the same time
    if (e.altKey) {
        switch (e.keyCode) {
            case 49: //1
                $("#btnDamage").click();
                break;
            case 50: //2
                $("#btnHeal").click();
                break;
            case 51: //3
                $("#btnHPTemp").click();
                break;
            case 78: //N
            case 192: //N
                nextTurn();
                break;
            case 65: //A
                loadPlayers();
                break;
            case 83: //S
                startCombat();
                break;
            case 79: //O
                $("#btnOdenar").click();
                break;
            case 80: //P
                limparTracker();
                break;
            default:
        }
    }
}
// register the handler 
document.addEventListener('keyup', doc_keyUp, false);