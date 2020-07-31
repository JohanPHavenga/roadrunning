<div class="ac-item <?= $active; ?>">
    <h5 class="ac-title"><i class="fa fa-<?= $race['racetype_icon']; ?>"></i><?= $race['race_name']; ?></h5>
    <div class="ac-content">
        <table class="table table-striped table-bordered race_table">
            <tbody>
                <tr>
                    <td style='width: 50%;'>Date</td>
                    <td colspan="2">
                        <?php
                        // virtual race
                        if ($edition_data['edition_status'] == 17) {
                            echo fdateHuman($date_list[1][0]['date_start']);
                            if ($date_list[1][0]['date_start']!=$date_list[1][0]['date_end']) {
                                echo " - ".fdateHuman($date_list[1][0]['date_end']);
                            }
//                            wts($date_list);
                        } else {
                            if (strtotime($race['race_date']) != strtotime($edition_data['edition_date'])) {
                                echo "<b class='text-danger'>" . fdateHuman($race['race_date']) . "</b>";
                            } else {
                                echo fdateHuman($race['race_date']);
                            }
                        }
                        ?>
                    </td>
                </tr>
                <?php
                if ($edition_data['edition_status'] != 17) {
                    ?>
                    <tr>
                        <td>Time</td>
                        <td colspan="2">Start: <b><?= ftimeMil($race['race_time_start']); ?></b>
                            <?php
                            if ($race['race_time_end'] > 0) {
                                ?>
                                <br>Cut-off: <b><?= ftimeMil($race['race_time_end']); ?></b>
                        </tr>
                        <?php
                    }
                    ?>
                    </td>
                    </tr>
                    <?php
                }
                ?>
                <tr>
                    <td>Distance</td>
                    <td colspan="2"><span class="badge badge-<?= $race['race_color']; ?>"><?= fraceDistance($race['race_distance']); ?></span></td>
                </tr>
                <tr>
                    <td>Race Type</td>
                    <td colspan="2"><?= $race['racetype_name']; ?></td>
                </tr>
                <?php
                if ($race['race_minimum_age'] > 0) {
                    ?>
                    <tr>
                        <td>Minimum age:</td>
                        <td colspan="2"><?= $race['race_minimum_age']; ?> years</td>
                    </tr>
                    <?php
                }
                if ($race['race_entry_limit'] > 0) {
                    ?>
                    <tr>
                        <td>Entry limit for the race:</td>
                        <td colspan="2"><?= $race['race_entry_limit']; ?></td>
                    </tr>
                    <?php
                }

                if ($race['race_fee_flat'] > 0) {
                    ?>
                    <tr>
                        <td>Race fee:</td>
                        <td>R<?= floatval($race['race_fee_flat']); ?></td>
                    </tr>
                    <?php
                } elseif ($race['race_fee_senior_licenced'] > 0) {
                    $info_text = "Races that are ran under the rules and regulations of the ASA (10km+) requires you to have a running license. You can either buy a temporarily license for the race you want to enter, or join a running club and purchase a permanent license number for the year. If you run more that 3x 10km+ races a year, it starts making financial sense to get a permanent number.";
                    ?>
                    <tr>
                        <th></th>
                        <th><a href="<?= base_url("faq/license#license"); ?>" title="<?= $info_text; ?>">Licensed <i class="fa fa-info-circle"></i></a></th>
                        <th><a href="<?= base_url("faq/license#license"); ?>" title="<?= $info_text; ?>">Unlicensed <i class="fa fa-info-circle"></i></a></th>
                    </tr>
                    <tr>
                        <td>Entry fees:</td>
                        <td>R<?= floatval($race['race_fee_senior_licenced']); ?></td>
                        <td>R<?= floatval($race['race_fee_senior_unlicenced']); ?></td>
                    </tr>
                    <?php
                    if ($race['race_fee_junior_licenced'] > 0) {
                        ?>
                        <tr>
                            <td>Junior (<20) Entry Fees:</td>
                            <td>R<?= floatval($race['race_fee_junior_licenced']); ?></td>
                            <td>R<?= floatval($race['race_fee_junior_unlicenced']); ?></td>
                        </tr>
                        <?php
                    }
                }
                if ($race['race_isover70free']) {
                    $fee70 = $race['race_fee_senior_unlicenced'] - $race['race_fee_senior_licenced'];
                    ?>
                    <tr>
                        <td>Great Grandmasters (70+):</td>
                        <td>Free</td>
                        <td>R<?= $fee70; ?></td>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
        <ul>
            <?php
            // LIMIT
            if ($race['race_entry_limit'] > 0) {
                echo "<li>The entry limit for this race <strong>" . $race['race_entry_limit'] . "</strong> entrants</li>";
            }
            // LIMIT
            if (!empty($race['race_address'])) {
                echo "<li><span style='color:red'><strong>NOTE</strong></span> that the <strong>starting address</strong> for this race differs from the end address:<br><strong>" . $race['race_address'] . "</strong></li>";
            }
            ?>
        </ul>
        <?= $race['race_notes']; ?>
        <?php
        if (!$in_past) {
            ?>
            <div>
                <a href="<?= base_url("event/" . $edition_data['edition_slug'] . "/entries"); ?>" class="btn btn-light btn-sm">
                    <i class="fa fa-edit" aria-hidden="true"></i>&nbsp;Entry Details</a>

                <a href="<?= base_url("training-programs/" . url_title($race['race_name'])); ?>" class="btn btn-light btn-sm">
                    <i class="fa fa fa-running"></i> Training Programs</a>
            </div>
            <?php
        }
        ?>
    </div>
</div>