$(".sortable").sortable();
$(".hpModifier").attr('disabled', true);
$("#divTurnTracker").hide();

var activePlayers = [];

function loadPlayers() {
    $("#divTurnTracker").show();
    configPlayers();
    turnTracker();
}
function loadEncounter() {
}

function limparTracker() {
    $('#tableTracker > tbody').html('');
}
function configPlayers() {
    activePlayers = [];
    $.each(players, function (key, player) {
        console.log(player)
        if (player['AdventurersPerAdventure']['ausente'] === '0') {
            temp = {}
            temp['id'] = 'p' + player['Adventurers']['id'];
            temp['nome'] = player['Adventurers']['name'];
            temp['HPMax'] = 100;
            temp['HPTemp'] = 0;
            temp['HPAtual'] = temp['HPMax'];
            temp['iniciativa'] = 0;
            activePlayers.push(temp);
        }
    });
}
function turnTracker() {
    $('#tableTracker > tbody').html('');

    $('#tableTracker').append('<tbody id="tbodyTracker" class="sortable"></tbody>');
    console.log(activePlayers)
    $.each(activePlayers, function (key, values) {
        var corHPAtual = '';
        var HPTemp = '';

        if (values['HPAtual'] < values['HPMax']) {
            corHPAtual = 'red';
        }
        if (values['HPTemp'] > 0) {
            HPTemp = '<font color="blue"> (' + values['HPTemp'] + ')';
        }
        var hp = '<font color="' + corHPAtual + '">' + values['HPAtual'] + '</font>/' + values['HPMax'] + HPTemp;
        var init = '<td><input class="initValue" type="number" min="-10" max="40" value="' + values['iniciativa'] + '"></input></td>';
        var combat = '<td><input id="hpModifier' + values['id'] + '" type="number" class="hpModifier"></td>';

        $('#tableTracker > tbody:last').append('<tr id="' + values['id'] + '">' + init + '<td><font color="blue">' + values['nome'] + '</font></td><td width="300"><div class="progressbar" id="progressbar' + values['id'] + '"><div class="progress-label">' + hp + '</div></div></td>' + combat + '</tr> ');

        $(function () {
            $("#progressbar" + values['id']).progressbar({
                max: values['HPMax'],
                value: values['HPAtual']
            });
        });

        if (values['HPAtual'] / values['HPMax'] <= 0.5) {
            $("#progressbar" + values['id']).addClass('ui-widget-header-red ui-widget-content-red');
            $("#progressbar" + values['id']).removeClass('ui-widget-header ui-widget-content');
        }
    });
    checkLocks();
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
    console.log(hpMap);
    $.each(hpMap, function (key, value) {
        if (value['hpMod'] !== 0) {
            $.each(activePlayers, function (key2, player) {
                if (value['playerId'] === player['id']) {
                    if (modifier === 1) {

                        if (activePlayers[key].HPTemp > 0) {
                            activePlayers[key].HPTemp -= value['hpMod'];
                            if (activePlayers[key].HPTemp < 0) {
                                activePlayers[key].HPAtual += activePlayers[key].HPTemp;
                                activePlayers[key].HPTemp = 0;
                            }
                        } else {
                            activePlayers[key].HPAtual -= value['hpMod'];
                        }
                        if (activePlayers[key].HPAtual < 0) {
                            activePlayers[key].HPAtual = 0;
                        }
                    } else if (modifier === 2) {
                        activePlayers[key].HPAtual += value['hpMod'];
                        if (activePlayers[key].HPAtual > activePlayers[key].HPMax) {
                            activePlayers[key].HPAtual = activePlayers[key].HPMax;
                        }
                    } else if (modifier === 3) {
                        if (value['hpMod'] > activePlayers[key].HPTemp) {
                            activePlayers[key].HPTemp = value['hpMod'];
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
        console.log(playerInit);
        $.each(activePlayers, function (key2, player) {
            if (player.id == playerInit.id) {
                activePlayers[key2].iniciativa = playerInit.iniciativa;
            }
        });
    });

    ordenarPorIniciativa();
    turnTracker();
});

function ordenarPorIniciativa() {
    var APlen = activePlayers.length;
    var j = 0;
    var swapped = true;
    while (swapped) {
        swapped = false;
        j++;
        for (i = 0; i < APlen - j; i++) {
            if (activePlayers[i]['iniciativa'] < activePlayers[i + 1]['iniciativa']) {
                temp = activePlayers[i];
                activePlayers[i] = activePlayers[i + 1];
                activePlayers[i + 1] = temp;
                swapped = true;
            }
        }
    }
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