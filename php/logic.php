<?php
$vehiclesGrossWeightInfo = array();
foreach ($mssql->getData("EXEC GetMaxGrossWeightVehicle") as $info) {
    $vehiclesGrossWeightInfo[$info['Id']] = array(
        'id' => (int)$info['Id'],
        'desc' => $info['Type Auto Description'],
        'weight' => $info['Max Weight']
    );
}
?>

<?php if (!$ajax): ?>
    <script>
        var grossDiff = 0;
        var vehiclesGrossWeightInfo = {
            <?php
            $firstIter = true;
            foreach ($vehiclesGrossWeightInfo as $id => $info) {
                if (!$firstIter) {
                    echo ',';
                }
                echo $id . ':{';
                echo "'desc': '" . $info['desc'] . "','weight':" . $info['weight'];
                echo '}';
                $firstIter = false;
            }
            ?>};
    </script>
    <div id="autocontrol-content-loading" class="text-center" style="margin-top: 40px; display: none;">
        <h1>Обработка данных</h1>
    </div>
    <div id="autocontrol-content">
        <script>
            $(document).ready(function ($) {
                $('.management-form').submit(function (event) {
                    event.preventDefault();

                    if (typeof(ttnPlan) != 'undefined' && !checkTtns()) {
                        return false;
                    }

                    if (grossDiff < 0) {
                        console.log('grrr', grossDiff);
                        $('#grossDiffFault').modal('show');
                        return false;
                    }

                    loading = true;
                    $('#autocontrol-content').fadeOut(300, function () {
                        if (loading == true) {
                            $('#autocontrol-content-loading').fadeIn(300);
                        }
                    });

                    forceSubmitForm();
                });

                function forceSubmitForm() {
                    console.log('force');
                    $.post('/index.php?ajax=1', $(".management-form").serialize())
                        .done(function (data) {
                            loading = false;
                            $('#autocontrol-content-loading').stop(true, true).hide();
                            $('#autocontrol-content').stop(true, true).html(data).fadeIn(300);
                        })
                        .fail(function () {
                            loading = false;
                            $('#autocontrol-content-loading').stop(true, true).hide();
                            $('#autocontrol-content').stop(true, true).html(data).fadeIn(500);
                            ;
                            alert("Ошибка отправки данных. Попробуйте снова");
                            return false;
                        });
                }

                $(document).keydown(function (e) {
                    //TODO insert keydown event handler in upper code part
                    if (e.which == 27) // escape
                    {
                        if ($('#closeVisitModal').is(':visible')) // Если открыто окно подтверждения отмены
                        {
                            $('#dropVisitCancel').click(); // escape отменяет отмену
                        }
                        else if ($('#grossDiffFault').is(':visible')) {
                            $('#grossDiffFaultCancel').click();
                        }
                        else // Если нет никакого окна подтверждения отмены
                        {
                            $('#dropVisit').click(); // escape вызывает окно подтверждения отмены
                        }
                    }
                    else if (e.which == 13) // enter
                    {
                        if ($('#closeVisitModal').is(':visible')) // Если открыто окно подтверждения отмены
                        {
                            $('#dropVisitOk').click(); // enter подтверждает отмену
                        }
                        else if ($('#grossDiffFault').is(':visible')) {
                            $('#grossDiffFaultOk').click();
                        }
                        else // Если нет никакого окна подтверждения отмены
                        {
                            if ($(e.target).attr('id') == 'ttn') // Если в фокусе поле для ввода ТТН, то сканируем ТТН
                            {
                                ttnScan();
                            }
                            else // Иначе отправляем данные по форме
                            {
                                $('.management-form').submit();
                            }
                        }
                        e.preventDefault();
                        return false;
                    }
                });
                $(document).on('click', '#dropVisit', function (event) {
                    event.preventDefault();
                    $('#closeVisitModal').modal('show');
                });
                $(document).on('click', '#dropVisitCancel', function (event) {
                    event.preventDefault();
                    $('#closeVisitModal').modal('hide');
                });
                $(document).on('click', '#dropVisitOk', function (event) {
                    $('#closeVisitModal').modal('hide');
                    event.preventDefault();
                    $.post('/index.php?ajax=1', {visit: 'drop', realTime: $('#realTime').val()})
                        .done(function (data) {
                            $('#autocontrol-content').fadeOut(500, function () {
                                $(this).html(data).fadeIn(500);
                            });
                        })
                        .fail(function () {
                            alert("Ошибка. Попробуйте снова");
                        });
                });
                $(document).on('click', '#grossDiffFaultCancel', function (event) {
                    event.preventDefault();
                    $('#grossDiffFault').modal('hide');
                });
                $(document).on('click', '#grossDiffFaultOk', function (event) {
                    event.preventDefault();
                    $('#grossDiffFault').modal('hide');
                    forceSubmitForm();
                });
            });
        </script>
        <?php endif; // if(!$ajax) ?>

        <?php
        $visit = $db->assoc1("SELECT * FROM `visits` WHERE `terminal`='" . $terminal->id . "' AND `close`='0' LIMIT 1;");
        $autoNumber = '';
        $code = '';
        if (isset($visit['direction'])) {
            $terminal->setCurrentDirection((int)$visit['direction']);
            $terminal->autoWeit = (int)$visit['autoWeit'];
            $terminal->itemWeit = (int)$visit['itemWeit'];
            $terminal->planWeight = (int)$visit['planWeight'];
            $terminal->autoType = (int)$visit['autoType'];
        }
        if (isset($_POST['visit'])) {
            if (($_POST['realTime'] - time()) < 2) {
                if (($_POST['visit'] === 'addCode') && ($visit === 0)) // Заезд на весы. Узнаем направление движения (на завод или с завода)
                {
                    // Проверка сопроводительного листа по Navision
                    $checkSlCar2Method = new nav\CheckSLCar2($user->login(), $user->realPassword(), $_POST['code'], '', '', '', '', 0, '');
                    $checkSlCar2Response = $navision->CheckSLCar2($checkSlCar2Method);
                    if (!$checkSlCar2Response->getReturn_value()) {
                        $code = $_POST['code'];
                        if ($checkSlCar2Response->getErrorText()) {
                            $formErrors[] = $checkSlCar2Response->getErrorText();
                        } else {
                            $formErrors[] = 'Отсутствует код ошибки (Exception)';
                        }
                    } else {
                        $openStatgesResponse = $navision->OpenStageBeforeKPP(new nav\OpenStageBeforeKPP($_POST['code'], ''));
                        if (!$openStatgesResponse->getReturn_value()) {
                            $code = $_POST['code'];
                            if ($openStatgesResponse->getErrorText()) {
                                $formErrors[] = $openStatgesResponse->getErrorText();
                            } else {
                                $formErrors[] = 'Не завершен предыдущий этап';
                            }
                        } else {
                            $terminal->autoWeit = (int)$checkSlCar2Response->getAutoWeit();
                            $terminal->itemWeit = (int)$checkSlCar2Response->getItemWeit();
                            $terminal->planWeight = (int)$checkSlCar2Response->getPlanWeight();
                            $terminal->autoType = (int)$checkSlCar2Response->getAutoType();
                            if ($checkSlCar2Response->getDirectionOfMotion() === 'Departure') {
                                $direction = Terminal::OUT;
                            } else { // Entrance
                                $direction = Terminal::IN;
                            }
                            $terminal->setCurrentDirection($direction);

                            if (!$relay->read($terminal->currentRelay('stateOut'))) { // Проверка - опущен ли другой шлагбаум // TODO: добавить опцию отказа от контроля шлагбаума
                                // Direction
                                // In  - Entrance
                                // Out - Departure
                                $db->query("UPDATE `visits` SET `close`='1' WHERE `close`='2' LIMIT 1;");
                                $db->query("
                                    INSERT INTO `visits`
                                    (`code`, `user`, `terminal`, `direction`, `dateStart`, `autoWeit`, `itemWeit`, `planWeight`, `autoType`)
                                    VALUE
                                    ('" . $_POST['code'] . "', '" . $user->id . "', '" . $terminal->id . "', '" . $direction . "', '" . time() . "', '" . $terminal->autoWeit . "', '" . $terminal->itemWeit . "', '" . $terminal->planWeight . "', '" . $terminal->autoType . "');
                                ");
                                $db->query("DELETE FROM `currentTtns` WHERE `terminal`='" . $terminal->id . "';");
                                $relay->write($terminal->currentRelay('gateIn'), TRUE);
                                $relay->write($terminal->currentRelay('gateOut'), FALSE);
                            } else { // TODO: добавить опцию отказа от контроля шлагбаума
                                $code = $_POST['code'];
                                $formErrors[] = 'Второй шлагбаум не опущен';
                            }
                        }
                    }
                } else if ($_POST['visit'] === 'drop' && $visit != 0) { // Отмена проезда
                    $db->query("DELETE FROM `visits` WHERE `terminal`='" . $terminal->id . "' AND `close`='0';");
                    $db->query("DELETE FROM `currentTtns` WHERE `terminal`='" . $terminal->id . "';");
                    if ((getWeight($terminal->ip) < 100) && (!$relay->read($terminal->stateIn))) {
                        $relay->write($terminal->currentRelay('gateIn'), FALSE);
                        $relay->write($terminal->currentRelay('gateOut'), FALSE);
                        $db->query("DELETE FROM `visits` WHERE `terminal`='" . $terminal->id . "' AND `close`='0';");
                    } else if (getWeight($terminal->ip) >= 100) {
                        $relay->write($terminal->currentRelay('gateIn'), TRUE);
                        $relay->write($terminal->currentRelay('gateOut'), FALSE);
                        $formErrors[] = 'Дождитесь, когда автотранспорт покинет весы';
                    } else if ($relay->read($terminal->stateIn)) {
                        $relay->write($terminal->currentRelay('gateIn'), TRUE);
                        $relay->write($terminal->currentRelay('gateOut'), FALSE);
                        $formErrors[] = 'Пожалуйста, закройте шлагбаум';
                    }
                } else if ($_POST['visit'] == 'complete' && $visit != 0) { // Машина на весах, отправляем полученный вес, номер, пломбу и сохраненные ттн
                    $ttnErrors = [];
                    $autoNumber = normalizeNumber($_POST['plate']);
                    if (($_POST['weight'] >= 100) && (!$relay->read($terminal->currentRelay('stateIn')))) {
                        if (!$visit['direction']) // Это выезд. ТТН проверяем только на выезде с завода.
                        {
                            $planTtns = $mssql->getData("TTN_List '" . $visit['code'] . "'");
                            $scanedTtns = $db->assoc("SELECT `ttn` FROM `currentTtns` WHERE `terminal` = '" . $terminal->id . "';");
                            $allTtnError = false;
                            foreach ($planTtns as $planTtn) {
                                if (strlen($planTtn[2]) == 0) {
                                    continue;
                                }
                                $ok = false;
                                foreach ($scanedTtns as $ttn) {
                                    if ($planTtn[3] == $ttn['ttn']) {
                                        $ok = true;
                                        break;
                                    }
                                }
                                if (!$ok && !$allTtnError) {
                                    $allTtnError = true;
                                    $ttnErrors[] = 'Не все ТТН были отсканированы';
                                }
                            }
                            foreach ($planTtns as $planTtn) {
                                $ttnItError = false;
                                $ttnInfo = $mssql->getData("
                                    DECLARE @err_msg NVARCHAR(256)
                                    DECLARE @return int
                                    EXEC
                                        @return = sp_invoice_bc_info
                                        @err_msg OUTPUT,
                                        '" . $terminal->id . "',
                                        '" . $user->login() . "',
                                        '" . $planTtn[3] . "'
                                    SELECT
                                        @return as result,
                                        @err_msg as err
                                ");

                                if (isset($ttnInfo[0]['result']) && $ttnInfo[0]['result'] != 1) {
                                    $ttnItError = true;
                                } else {
                                    $ttnSend = $mssql->getData("
                                        DECLARE @err_msg NVARCHAR(256)
                                        declare @return int
                                        EXEC @return = sp_departure_car @err_msg OUTPUT, '" . $terminal->id . "', '" . $user->login() . "', '" . $autoNumber . "', '" . $planTtn[3] . "'
                                        SELECT @return as result, @err_msg as err
                                    ");
                                    if (isset($ttnSend[0]['result']) && $ttnSend[0]['result'] != 1) {
                                        $ttnItError = true;
                                    }
                                }

                                if ($ttnItError) {
                                    $errStr = 'Проблема подтверждения ТТН ' . $planTtn[2] . ' (' . $planTtn[3] . ').';
                                    if (isset($ttnInfo) && isset($ttnInfo[0])) {
                                        $errStr .= ' info: ' . $ttnInfo[0]['err'];
                                    }
                                    if (isset($ttnSend) && isset($ttnSend[0])) {
                                        $errStr .= ' send: ' . $ttnSend[0]['err'];
                                    }
                                    $ttnErrors[] = $errStr;
                                }
                            }
                        }

                        $terminal->saveSeal(isset($_POST['seal']) ? $_POST['seal'] : ''); // номер пломбы сохраняем в $terminal->seal и БД

                        if (count($ttnErrors) === 0) {
                            // Отправка данных в Navision
                            // Пример обращения departure?login=1&password=2&barcode=3&weight=4&tirno=5&seal=6
                            if ($visit['direction']) { // Для въезда на завод CheckArrivalCar2
                                $method = new nav\CheckArrivalCar2($user->login(), $user->realPassword(), $visit['code'], (float)$_POST['weight'], $autoNumber, $terminal->seal, '', (float)$_POST['totalWeight'], $_POST['autoType']);
                                $response = $navision->CheckArrivalCar2($method);
                            } else { // Для выезда с завода CheckDepatureCar1
                                $method = new nav\CheckDepatureCar2($user->login(), $user->realPassword(), $visit['code'], (float)$_POST['weight'], $autoNumber, $terminal->seal, '', (float)$_POST['totalWeight'], $_POST['autoType']);
                                $response = $navision->CheckDepatureCar2($method);
                            }
                            if ($response->getReturn_value()) {
                                $relay->write($terminal->currentRelay('gateOut'), TRUE);
                                $relay->write($terminal->currentRelay('gateIn'), FALSE);
                                $db->query("UPDATE `visits` SET `auto`='" . $autoNumber . "', `weight`='" . $_POST['weight'] . "', `weightA`='" . $_POST['weightA'] . "', `close`='2', `dateEnd` = '" . time() . "' WHERE `terminal`='" . $terminal->id . "' AND `close`='0' LIMIT 1;");
                                $db->query("DELETE FROM `currentTtns` WHERE `terminal`='" . $terminal->id . "';");
                                $formSuccess[] = '<span class="label label-infopan">' . myGetDate('textDateTime') . '</span><br>Успешно добавлено посещение авто с номером ' . $autoNumber . ', весом ' . $_POST['weight'] . '.<br>Сопроводительный лист №' . $visit['code'];
                            } else {
                                $code = $_POST['code'];
                                if ($response->getErrorText()) {
                                    $formErrors[] = $response->getErrorText();
                                } else {
                                    $formErrors[] = 'Ошибка въезда/выезда 2';
                                }
                            }
                        } else {
                            $code = $_POST['code'];
                            foreach ($ttnErrors as $ttnErr) {
                                $formErrors[] = $ttnErr;
                            }
                        }
                    }
                    if ($_POST['weight'] < 100) {
                        $formErrors[] = 'Неправильный вес (' . $_POST['weight'] . ')';
                    }

                    // TODO: добавить опцию отказа от контроля шлагбаума
                    if ($relay->read($terminal->currentRelay('stateIn'))) {
                        $formErrors[] = 'Пожалуйста, закройте один шлагбаум, чтобы открыть другой';
                    }
                }
            } else {
                $formErrors[] = 'Ошибка отправки формы (предотвращение случайной отправки)';
            }
        }
        $visit = $db->assoc1("SELECT * FROM `visits` WHERE `terminal`='" . $terminal->id . "' AND `close`='0';");
        ?>
        <form class="form-horizontal management-form" autocomplete="off" style="margin-top: 2em;" method="post" action="/">
            <fieldset>
                <div id="weight_error" class="alert alert-error" style="display: none;">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <span id="weight_error_string"></span>
                </div>
                <?php
                foreach ($formErrors as $errorString) {
                    echo '
                <div class="alert alert-error">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    ' . $errorString . '
                </div>';
                }
                foreach ($formSuccess as $successString) {
                    echo '
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    ' . $successString . '
                </div>';
                }
                ?>
                <?php if ($visit == 0): ?>
                    <!-- Form Name -->
                    <legend style="text-align: center;">Ввод сопроводительного листа</legend>

                    <!-- Text input-->
                    <div class="control-group">
                        <label class="control-label" style="font-size: .95em;">Сопроводительный лист</label>

                        <div class="controls">
                            <input id="code" name="code" enter="1" type="text" class="input-xlarge" value="<?= $code ?>" required autofocus>
                        </div>
                    </div>

                    <!-- Button (Double) -->
                    <div class="control-group">
                        <div class="controls">
                            <input id="SlCheckSubmit" type="submit" class="btn btn-success" value="Отправить данные">
                        </div>
                    </div>

                <input type="hidden" name="visit" value="addCode"/>

                    <script>
                        $(document).ready(function ($) {
                            $("input[name=direction]:radio").click(function () {
                                $("#code").focus();
                            });
                            /*
                             $("#code").keyup(function() {
                             if($("#code").val().length == 13)
                             {
                             $("#SlCheckSubmit").click();
                             }
                             });
                             */
                        });
                    </script>
                <?php else: // $visit!=0       ?>
                    <!-- Form Name -->
                    <legend style="text-align: center;">Заполнение данных проезда
                        (<?php if ($visit['direction']) echo 'Въезд'; else echo 'Выезд'; ?>)
                    </legend>

                    <!-- Text input-->
                    <div class="control-group">
                        <label class="control-label" style="font-size: .95em;">Сопроводительный лист</label>

                        <div class="controls">
                            <input id="code" name="code" type="text" value="<?= $visit['code'] ?>" readonly class="input-xlarge">
                        </div>
                    </div>

                    <!-- Appended checkbox -->
                    <div class="control-group">
                        <label class="control-label">Показание весов</label>

                        <div class="controls">
                            <div class="input-append">
                                <input id="weight" name="weight" class="span2" type="text" readonly="readonly" value="0" style="width: 228px;">
              <span class="add-on">
                <input type="checkbox" name="handsWeight" id="handsWeight" value="1" style="display: block; float: left;"/>
                <label for="handsWeight" style="float: left; margin-left: 5px;">Ввести руками</span>
                                </span>
                            </div>
                        </div>
                    </div>
                <input id="weightA" name="weightA" type="hidden" value="0">
                <?php if (!$visit['direction']) { // Показываем въездной вес только если это выезд с завода { ?>
                    <div class="control-group">
                        <label class="control-label">Вес на въезде</label>

                        <div class="controls">
                            <input id="autoWeit" name="autoWeit" type="text" value="<?= $terminal->autoWeit ?>" readonly class="input-xlarge">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Вес продукции</label>

                        <div class="controls">
                            <input id="itemWeit" name="itemWeit" type="text" value="<?= $terminal->itemWeit ?>" readonly class="input-xlarge">
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" style="font-size: .95em; height: 2em;">Разница
                            весов<br><span style="display: block; font-size: 11px; margin-top: -6px;">выезд - (въезд + продукция)</span></label>

                        <div class="controls">
                            <input id="diffWeit" name="diffWeit" type="text" value="" readonly class="input-xlarge">
                        </div>
                    </div>
                <?php } ?>

                    <div class="control-group">
                        <label class="control-label" style="font-size: .95em;">Тип авто</label>

                        <div class="controls">
                            <select id="autoType" name="autoType" required style="width: 100%;">
                                <option value="">Выберите тип авто</option>
                                <?php
                                foreach ($vehiclesGrossWeightInfo as $id => $info) {
                                    echo '<option value="' . $id . '"';
                                    if ($terminal->autoType == $id) {
                                        echo ' selected';
                                    }
                                    echo '>' . $info['desc'] . '</option>';
                                }
                                ?>};
                                ?>
                            </select>
                            <script>
                                var maxGrossWeight = 0;
                                $('#autoType').change(function () {
                                    if (vehiclesGrossWeightInfo[parseInt($(this).val())]) {
                                        maxGrossWeight = vehiclesGrossWeightInfo[parseInt($(this).val())].weight;
                                    } else {
                                        maxGrossWeight = 0;
                                    }
                                    $('#maxGrossWeight').val(maxGrossWeight);
                                    $('#weight').change();
                                });
                            </script>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" style="font-size: .82em;">Максимально допустимый вес</label>

                        <div class="controls">
                            <input id="maxGrossWeight" type="text" class="input-xlarge" value="" readonly>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label" style="font-size: .95em;"><?=($visit['direction'] ? 'Расчетный вес на выезде' : 'Вес авто и груза') ?></label>

                        <div class="controls">
                            <input id="totalWeightInfo" type="text" class="input-xlarge" value="" readonly>
                        </div>
                        <input type="hidden" id="totalWeight" name="totalWeight" value="0">
                    </div>

                    <script>
                        var weight = 0;
                        var autoWeight = true;

                        function weightUpdate() {
                            $.post('/weight').done(
                                function (data) {
                                    $('#weight').change();
                                    var dataArray = data.split('\n');
                                    if (dataArray[0] == 'connection_error') {
                                        $('#weight_error_string').html(dataArray[1]);
                                        if (!$('#weight_error').is(":visible")) {
                                            //$('#weight_error').show();
                                        }
                                    }
                                    else if ((parseInt(data) >= 0) && (weight != parseInt(data))) {
                                        if ($('#weight_error').is(":visible")) {
                                            $('#weight_error').hide();
                                        }
                                        var weight = parseInt(data);
                                        $('#weightA').val(weight);
                                        if (autoWeight)
                                            $('#weight').val(weight).change();
                                    }
                                });
                        }

                        weightUpdate();

                        setInterval(function () {
                            weightUpdate();
                        }, 1000);
                        $('#handsWeight').change(function () {
                            if ($('#handsWeight').is(":checked")) {
                                autoWeight = false;
                                $('#weight').removeAttr('readonly').focus();
                            }
                            else {
                                autoWeight = true;
                                $('#weight').attr('readonly', 'readonly').val(weight);
                            }
                        });

                        $('#weight').change(function () {
                            <?php if(!$visit['direction']) { // Считаем разницу весов только если это выезд с завода { ?>
                            var diff = parseInt($('#weight').val()) - (parseInt($('#autoWeit').val()) + parseInt($('#itemWeit').val())),
                                diffDom = $('#diffWeit');
                            diffDom.val(diff);
                            if (diff > 200) {
                                diffDom.css('background', '#f22').css('color', '#fff');
                            } else {
                                diffDom.css('background', '').css('color', '');
                            }
                            <?php } ?>
                            var totalWeight = parseInt($('#weight').val()) + parseInt(<?=($visit['direction'] ? $terminal->planWeight : 0) ?>);
                            var totalWeightInfo = $('#totalWeightInfo');
                            totalWeightInfo.val(totalWeight);
                            grossDiff = maxGrossWeight - totalWeight;
                            if (grossDiff < 0) {
                                $('#totalWeight').val(totalWeight);
                                totalWeightInfo.css('background', '#f22').css('color', '#fff');
                            } else {
                                $('#totalWeight').val(0);
                                totalWeightInfo.css('background', '').css('color', '');
                            }

                        });

                        $('#autoType').change();
                    </script>

                    <!-- Text input-->
                    <div class="control-group">
                        <label class="control-label">Номер авто</label>

                        <div class="controls">
                            <div class="input-append">
                                <input id="plate" name="plate" class="span2" type="text" readonly="readonly" value="" style="width: 228px;">
              <span class="add-on">
                <input type="checkbox" name="handsPlate" id="handsPlate" value="1" style="display: block; float: left;"/>
                <label for="handsPlate" style="float: left; margin-left: 5px;">Ввести руками</span>
                                </span>
                            </div>
                        </div>
                    </div>

                <input id="plateA" name="plateA" type="hidden" value="0">

                    <script>
                        let plate = '';
                        let autoPlate = true;

                        function insertPlate() {
                            $.post('/plate').done(
                                function (data) {
                                    let dataArray = data.split('\n');
                                    if (dataArray[0] === 'connection_error') {
                                        $('#plate_error_string').html(dataArray[1]);
                                        if (!$('#plate_error').is(":visible")) {
                                            $('#plate_error').show();
                                        }
                                    } else {
                                        plate = escape(data);
                                        $('#plateA').val(plate);
                                        if (autoPlate) {
                                            $('#plate').val(plate);
                                        }
                                    }
                                }
                            );
                        }
                        insertPlate();
                        setInterval(function () {
                            insertPlate();
                        }, 3000);
                        $('#handsPlate').change(function () {
                            if ($('#handsPlate').is(":checked")) {
                                autoPlate = false;
                                $('#plate').removeAttr('readonly').focus();
                            } else {
                                autoPlate = true;
                                $('#plate').attr('readonly', 'readonly').val(plate);
                            }
                        });
                    </script>

                <?php if (!$visit['direction']) { // Работаем с пломбой и ТТН только если это выезд с завода { ?>
                    <div class="control-group">
                        <label class="control-label">Пломба</label>

                        <div class="controls">
                            <input id="seal" name="seal" type="text" value="<?= $terminal->seal ?>" class="input-xlarge" required>
                        </div>
                    </div>
                    <div class="control-group ttn-scan control-group-ttn">
                        <label class="control-label">Сканировать ТТН:</label>

                        <div class="controls">
                            <div class="input-append">
                                <input id="ttn" name="ttn" type="text" value="" style="width: 234px;" autofocus>
                        <span class="add-on">
                            <button id="ttnScan" class="btn btn-success">Сканировать</button>
                        </span>
                            </div>
                        </div>
                    </div>
                    <div id="ttnAbout" class="control-group-ttn">&nbsp;</div>
                    <div class="control-group ttn-selectors control-group-ttn">
                        <div>
                            <select size="8" id="ttnPlan" readonly disabled></select>
                            <label>Требуемые ТТН</label>
                        </div>
                        <div>
                            <select size="8" id="ttnScaned" readonly disabled></select>
                            <label>Отсканированные ТТН</label>
                        </div>
                    </div>
                <input id="plateA" name="plateA" type="hidden" value="">
                    <script>
                        var ttnPlan = {
                            <?php
                            $planTtns = $mssql->getData("TTN_List '" . $visit['code'] . "'");
                            $ttnPlanIter = 0;
                            foreach ($planTtns as $planTtn) {
                                if (strlen($planTtn[2]) == 0) {
                                    continue;
                                }
                                if ($ttnPlanIter != 0) {
                                    echo ', ';
                                }
                                ++$ttnPlanIter;
                                echo "'" . $planTtn[3] . "': '" . $ttnPlanIter . '. ' . $planTtn[2] . "'";
                            }
                            ?>
                        };
                        var ttnPlanCount = 0;
                        for (var i in ttnPlan) {
                            ++ttnPlanCount;
                        }
                        if (ttnPlanCount == 0) {
                            $('.control-group-ttn').hide();
                        }
                        var ttnScanedArray = [<?php
                            $scanedTtns = $db->assoc("SELECT `ttn` FROM `currentTtns` WHERE `terminal` = '" . $terminal->id . "';");
                            $first = true;
                            foreach ($scanedTtns as $ttn) {
                                if (!$first) {
                                    echo ', ';
                                } else {
                                    $first = false;
                                }
                                echo "'" . $ttn['ttn'] . "'";
                            }
                            ?>];
                        var ttnScaned = {};
                        ttnScanedArray.forEach(function (ttn) {
                            if (ttn in ttnPlan) {
                                ttnScaned[ttn] = ttnPlan[ttn];
                            }
                        });
                        function fillSelectWithSortedObject(selectObject, fillerObject) {
                            selectObject.html('');
                            var keys = Object.keys(fillerObject);
                            var i, len = keys.length;

                            keys.sort();

                            for (i = 0; i < len; i++) {
                                var k = keys[i];
                                selectObject.append('<option value="' + k + '">' + fillerObject[k] + '</option>');
                            }
                        }
                        fillSelectWithSortedObject($('#ttnPlan'), ttnPlan);
                        fillSelectWithSortedObject($('#ttnScaned'), ttnScaned);
                        $('#ttnPlan, #ttnScaned').change(function () {
                            if ($(this).attr('id') == 'ttnScaned') {
                                $('#ttnPlan').val('');
                            }
                            else {
                                $('#ttnScaned').val('');
                            }
                            $('#ttnAbout').html($(this).val() + ': ' + ttnPlan[$(this).val()]);
                        });

                        function ttnScan() {
                            var ttn = $('#ttn').val();
                            if (!(ttn in ttnPlan)) {
                                $('#ttnAbout').html('Введённый ТТН "' + ttn + '" не требуется');
                                return;
                            }
                            $.post('/ttnScan', {ttn: ttn},
                                function (data) {
                                    if (data == 'ok') {
                                        ttnScaned[ttn] = ttnPlan[ttn];
                                        fillSelectWithSortedObject($('#ttnScaned'), ttnScaned);
                                        $('#ttnAbout').html('&nbsp;');
                                    }
                                    else {
                                        $('#ttnAbout').html(data);
                                    }
                                    $('#ttn').val('');
                                });
                        }
                        $('#ttnScan').click(function (e) {
                            ttnScan();
                            e.preventDefault();
                            return false;
                        });

                        function checkTtns() {
                            for (var ttn in ttnPlan) {
                                if (!(ttn in ttnScaned)) {
                                    $('#ttnAbout').html('Не все ТТН отсканированы!');
                                    return false;
                                }
                            }
                            return true;
                        }
                    </script>
                <?php } // Работаем с пломбой и ТТН только если это выезд с завода } ?>

                    <!-- Button (Double) -->
                    <div class="control-group">
                        <div class="controls">
                            <button id="submitVisit" type="submit" class="btn btn-success">Отправить данные</button>
                            <button id="dropVisit" class="btn btn-danger">Отменить проезд</button>
                        </div>
                    </div>


                    <div class="modal hide fade" id="grossDiffFault">
                        <div class="modal-header">
                            <h3>Вес авто с продукцией превышает нормы</h3>
                        </div>
                        <div class="modal-body">
                            <p>Нажмите Enter, чтобы все равно продолжить отправку формы и пропустить авто, иначе нажмите
                                ESC</p>
                        </div>
                        <div class="modal-footer">
                            <button id="grossDiffFaultOk" class="btn btn-danger">Все равно пропустить</button>
                            <button id="grossDiffFaultCancel" class="btn btn-primary">Запретить проезд</button>
                        </div>
                    </div>

                    <div class="modal hide fade" id="closeVisitModal">
                        <div class="modal-header">
                            <h3>Подтвердите отмену</h3>
                        </div>
                        <div class="modal-body">
                            <p>Нажмите Enter, чтобы подтвердить отмену посещения авто или ESC, чтобы продолжить
                                заполнение формы</p>
                        </div>
                        <div class="modal-footer">
                            <button id="dropVisitOk" class="btn btn-danger">Да, отменить проезд</button>
                            <button id="dropVisitCancel" class="btn btn-primary">Нет, продолжить заполнение формы
                            </button>
                        </div>
                    </div>
                <input type="hidden" name="visit" value="complete"/>
                <?php endif; // else: if($visit==0): ?>
                <input type="hidden" name="realTime" id="realTime" value="<?= time() ?>">
            </fieldset>
        </form>


        <?php if (!$ajax): ?>
    </div> <!-- autocontrol-content -->
<?php endif; ?>