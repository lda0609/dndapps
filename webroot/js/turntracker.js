$(".hpModifier").attr('disabled', true);
$("#divTurnTracker").hide();

var allFighters = [];

function loadPlayers() {
    $("#divTurnTracker").show();
    configPlayers();
    turnTracker();
}
function loadEncounter(monsterList) {
    $("#divTurnTracker").show();
    $.each(monsterList, function (key, monster) {
        temp = {}
        temp['id'] = 'm' + key;
        temp['nome'] = monster['name'];
        temp['HPMax'] = monster['hp'];
        temp['HPTemp'] = 0;
        temp['HPAtual'] = temp['HPMax'];
        temp['iniciativa'] = 0;
        allFighters.push(temp);
    });
    turnTracker();
}

function limparTracker() {
    allFighters = [];
    $('#tableTracker > tbody').html('');
}
function configPlayers() {
    $.each(players, function (key, player) {
        if (player['AdventurersPerAdventure']['ausente'] === '0') {
            temp = {}
            temp['id'] = 'p' + player['Adventurers']['id'];
            temp['nome'] = player['Adventurers']['name'];
            temp['HPMax'] = 100;
            temp['HPTemp'] = 0;
            temp['HPAtual'] = temp['HPMax'];
            temp['iniciativa'] = 0;
            allFighters.push(temp);
        }
    });
}
function turnTracker() {
    $('#tableTracker > tbody').html('');
    $('#tableTracker').append('<tbody id="tbodyTracker" class="sortable"></tbody>');
    $.each(allFighters, function (key, values) {
        var corHPAtual = '';
        var HPTemp = '';

        var corNome = 'blue';
        if (values['id'].substring(0, 1) === 'm') {
            corNome = 'red';
        }
        if (values['HPAtual'] < values['HPMax']) {
            corHPAtual = 'red';
        }
        if (values['HPTemp'] > 0) {
            HPTemp = '<font color="blue"> (' + values['HPTemp'] + ')';
        }
        var hp = '<font color="' + corHPAtual + '">' + values['HPAtual'] + '</font>/' + values['HPMax'] + HPTemp;
        var init = '<td><input class="initValue" type="number" min="-10" max="40" value="' + values['iniciativa'] + '"></input></td>';
        var combat = '<td><input id="hpModifier' + values['id'] + '" type="number" class="hpModifier"></td>';

        $('#tableTracker > tbody:last').append('<tr id="' + values['id'] + '">' + init + '<td><font color="' + corNome + '">' + values['nome'] + '</font></td><td width="300"><div class="progressbar" id="progressbar' + values['id'] + '"><div class="progress-label">' + hp + '</div></div></td>' + combat + '</tr> ');
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
            },
        });
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
            console.log(allFighters);
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
        console.log(playerInit);
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
//                console.log(value2);
                temp.push(value2);
            }
        });
    });
    allFighters = temp;
//    console.log(allFighters);
//    console.log(temp);
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

