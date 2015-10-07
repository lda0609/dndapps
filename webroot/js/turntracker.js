$(".hpModifier").attr('disabled', true);
$("#divTurnTracker").hide();

var allFighters = [];

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
        var id = $('.currentTurn').attr("id");
        fighter = getFighterById(id);
        load_side_frame(fighter, 'td_left_size');
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
    $("#divTurnTracker").hide();

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
            if (player['AdventurersPerAdventure']['ausente'] === '0') {
                temp = {};
                temp['details'] = {};
                temp['conditions'] = [];
                temp['id'] = 'p' + player['Adventurers']['id'];
                temp['name'] = player['Adventurers']['name'];
                temp['HPMax'] = player['CharacterProgression']['hit_point_max'];
                temp['HPTemp'] = 0;
                temp['HPAtual'] = temp['HPMax'];
                temp['iniciativa'] = 0;
                temp['details']['ac'] = player['CharacterProgression']['ac'];
                temp['details']['speed'] = player['CharacterProgression']['speed'];
                temp['details']['str'] = player['CharacterProgression']['strength'];
                temp['details']['dex'] = player['CharacterProgression']['dextery'];
                temp['details']['con'] = player['CharacterProgression']['constitution'];
                temp['details']['int'] = player['CharacterProgression']['intelligence'];
                temp['details']['wis'] = player['CharacterProgression']['wisdom'];
                temp['details']['cha'] = player['CharacterProgression']['charisma'];
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
                        if (allFighters[key].HPTemp > 0) {
                            allFighters[key].HPTemp -= value['hpMod'];
                            if (allFighters[key].HPTemp < 0) {
                                allFighters[key].HPAtual += allFighters[key].HPTemp;
                                allFighters[key].HPTemp = 0;
                            }
                        } else {
                            allFighters[key].HPAtual -= value['hpMod'];
                        }
                        if (allFighters[key].HPAtual < 0) {
                            allFighters[key].HPAtual = 0;
                        }
                    } else if (modifier === 2) {
                        allFighters[key].HPAtual += value['hpMod'];
                        if (allFighters[key].HPAtual > allFighters[key].HPMax) {
                            allFighters[key].HPAtual = allFighters[key].HPMax;
                        }
                    } else if (modifier === 3) {
                        if (value['hpMod'] > allFighters[key].HPTemp) {
                            allFighters[key].HPTemp = value['hpMod'];
                        }
                    }
                }
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
        var id = $('.currentTurn').attr("id");
        fighter = getFighterById(id);
        load_side_frame(fighter, 'td_left_size');
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
    var className = 'name_PC';
    var rows = '';
    if (fighter['id'].substring(0, 1) === 'm') {
        className = 'name_NPC';
        rows += '<tr><td class="' + className + '">' + fighter.name + '</td></tr>';
    } else {
        rows += '<tr><td colspan="4" class="' + className + '">' + fighter.name + '</td></tr>';
        rows += '<tr><td>AC</td><td>' + fighter.details.ac + '</td><td>SPD</td><td>' + fighter.details.speed + '</td></tr>';
        rows += '<tr><td>STR</td><td>' + fighter.details.str + '</td><td>DEX</td><td>' + fighter.details.dex + '</td></tr>';
        rows += '<tr><td>CON</td><td>' + fighter.details.con + '</td><td>INT</td><td>' + fighter.details.int + '</td></tr>';
        rows += '<tr><td>WIS</td><td>' + fighter.details.wis + '</td><td>CHA</td><td>' + fighter.details.cha + '</td></tr>';
    }
    var content = '<table class=tracker-side-frame>' + rows + '</table>';

    conditions = ['Blinded', 'Charmed', 'Deafened', 'Exhaustion', 'Frightened', 'Grappled', 'Incapacitated', 'Invisible', 'Paralyzed', 'Petrified', 'Poisoned', 'Prone', 'Restrained', 'Stunned', 'Unconscious']
    html_conditions = '<table class="' + side + ' tracker-side-frame"><tr>';
    var cont = 1;
    console.log(fighter.conditions);
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
    $('#' + side).html(content + html_conditions);
}

$('body').on('click', '.conditions', function () {
    var condition = $(this).attr('name');
    var side = $(this).closest('table').attr("class");
    $(this).toggleClass('hasCondition');

    if (side === 'td_left_size') {
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
    console.log(e.keyCode)
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