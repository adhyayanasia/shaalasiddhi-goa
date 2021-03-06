<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<div class="filterByAjax client-list" data-action="<?php echo $this->_action; ?>" data-controller="<?php echo $this->_controller; ?>" >
    <h1 class="page-title">
        Schools
        <?php if ($canCreateClient) { ?>
            <a href="?controller=client&action=createClient&ispop=1" class="btn btn-primary pull-right vtip execUrl" title="Click to add school." id="addClientBtn">Add New</a>
        <?php } ?>
        <div class="clr"></div>
    </h1>
    <div class="asmntTypeContainer">						
        <?php
        $ajaxFilter = new ajaxFilter();
        $ajaxFilter->addDropDown("client_institution_id", $client_institution_type, 'client_institution_id', 'institution', $filterParam["client_institution_id"], "Inst. Type");

        $ajaxFilter->addTextbox("name", $filterParam["name_like"], "School");
        
        $ajaxFilter->addDropDown("country_id", $countries, 'country_id', 'country_name', $filterParam["country_id"], "Country");
        $ajaxFilter->addDropDown("state_id", $states, 'state_id', 'state_name', $filterParam["state_id"], "State");
        $ajaxFilter->addDropDown("city", $cities, 'city_name', 'city_name', $filterParam["city_like"], "City");
        if ($canCreateClient) {
            $ajaxFilter->addHiddenEtc("stat_id", $stats[0]['state_id'],'');
            $ajaxFilter->addDropDown("zone_id", $zones, 'zone_id', 'zone_name', $filterParam["zone_id"], "Zone");
            $ajaxFilter->addDropDown("network_id", $networks, 'network_id', 'network_name', $filterParam["network_id"], "Block");
            $ajaxFilter->addDropDown("province_id", $provinces, 'province_id', 'province_name', $filterParam["province_id"], "Hub");
        }
        $ajaxFilter->generateFilterBar(1);
        ?>

        <div class="tableHldr">
            <table class="cmnTable">
                <thead>
                    <tr>
                        <th style="width:150px;" data-value="client_type" class="sort <?php echo $orderBy == "client_type" ? "sorted_" . $orderType : ""; ?>">Institution Type</th>
                        <th style="width:300px;" data-value="client_name" class="sort <?php echo $orderBy == "client_name" ? "sorted_" . $orderType : ""; ?>">School Name</th>
                        <th style="width:250px;" data-value="street" class="sort <?php echo $orderBy == "street" ? "sorted_" . $orderType : ""; ?>">Street</th>
                        <th data-value="country" class="sort <?php echo $orderBy == "country" ? "sorted_" . $orderType : ""; ?>">Country</th>
                        <th data-value="state" class="sort <?php echo $orderBy == "state" ? "sorted_" . $orderType : ""; ?>">State</th>
                        <th data-value="city" class="sort <?php echo $orderBy == "city" ? "sorted_" . $orderType : ""; ?>">City</th>
                        <th style="width:100px;" data-value="zone" class="sort <?php echo $orderBy == "zone" ? "sorted_" . $orderType : ""; ?>">Zone</th>
                        <th style="width:100px;" data-value="network" class="sort <?php echo $orderBy == "network" ? "sorted_" . $orderType : ""; ?>">Block</th>
                        <th style="width:100px;" data-value="province" class="sort <?php echo $orderBy == "province" ? "sorted_" . $orderType : ""; ?>">Hub</th>
                        <th style="width:100px;" data-value="create_date" class="sort <?php echo $orderBy == "create_date" ? "sorted_" . $orderType : ""; ?>">Created on</th>
                        <?php if ($canCreateClient) { ?>
                            <th>Action</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($clients))
                        foreach ($clients as $client) {
                            ?>
                            <tr>
                                <td><?php echo $client['institution']; ?></td>
                                <td><?php echo $client['client_name']; ?></td>
                                <td><?php echo $client['street']; ?></td>
                                <td><?php echo $client['country_name']; ?></td>
                                <td><?php echo $client['state_name']; ?></td>
                                <td><?php echo $client['city_name'] ? $client['city_name'] : $client['city']; ?></td>																														

                                <td><?php echo $client['zone_name']; ?></td>
                                <td><?php echo $client['network_name']; ?></td>
                                <td><?php echo $client['province_name']; ?></td>
                                <td><?php echo ChangeFormat($client['created_on']); ?></td>
                                <?php if ($canCreateClient) { ?>
                                    <td><a href="?controller=client&action=editClient&id=<?php echo $client['client_id']; ?>&ispop=1" class="execUrl btn"><i class="vtip glyphicon glyphicon-pencil" title="Update School"></i></a>
                                    </td>
                                <?php } ?>
                            </tr>
                            <?php
                        } else {
                        ?>
                        <tr>
                            <td colspan="<?php echo $canCreateClient ? 8 : 6; ?>">No school found</td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <?php echo $this->generateAjaxPaging($pages, $cPage); ?>

        <div class="ajaxMsg"></div>
    </div>
</div>
						