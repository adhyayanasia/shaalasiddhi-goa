<?php
/* Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
    This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
    LICENSE file in the root directory of this source tree.*/
?>

<?php if($data){     ?>

           <div class="chartBoxOuter lbg" style="min-width: 40%;width:100%;">
               <div class="btmLegends topRight">
                <div><span class="lbx" style="background-color:#38c176"></span>Level 3</div>
                <div><span class="lbx" style="background-color:#d2a828"></span>Level 2</div>
                <div><span class="lbx" style="background-color:#ff4c4c"></span>Level 1</div>
            </div>
               <div class="leftLegend"><div>100%</div><div>90%</div><div>80%</div><div>70%</div><div>60%</div><div>50%</div><div>40%</div><div>30%</div><div>20%</div><div>10%</div><div>0%</div></div>
               <div class="chartBox zone_box">                  
                      
                       <?php foreach($data as $key => $value) {  
                       $totalKpaRating = (isset($data[$key]['Level-1'])?$data[$key]['Level-1'][0]['level1']:0)+(isset($data[$key]['Level-2'])?$data[$key]['Level-2'][0]['level1']:0)+(isset($data[$key]['Level-3'])?$data[$key]['Level-3'][0]['level1']:0);
                       $pcLevel1 = isset($data[$key]['Level-1'])?round(($data[$key]['Level-1'][0]['level1']/$totalKpaRating)*100,2):0;
                       $pcLevel2 = isset($data[$key]['Level-2'])?round(($data[$key]['Level-2'][0]['level1']/$totalKpaRating)*100,2):0;
                       $pcLevel3 = isset($data[$key]['Level-3'])?round(($data[$key]['Level-3'][0]['level1']/$totalKpaRating)*100,2):0;
                       ?>
                       <a href="#<?php echo $key ?>"><div class="lineBar cb" style="height:211px;width:7%;margin:0 2% 0 0;" onclick="clusterReport('level1',<?php echo $key; ?>,'<?php echo $block;?>')">
                           <div style="height:<?php echo round(2.11*$pcLevel3,2)."px";?>;background-color:#38c176"><span><?php echo $pcLevel3>0?$pcLevel3:'';?></span></div>
                           <div style="height:<?php echo round(2.11*$pcLevel2,2)."px";?>;background-color:#d2a828"><span><?php echo $pcLevel2>0?$pcLevel2:'';?></span></div>
                           <div style="height:<?php echo round(2.11*$pcLevel1,2)."px";?>;background-color:#ff4c4c"><span><?php echo $pcLevel1>0?$pcLevel1:'';?></span></div>
                           <label class="lineLbl" style="left:-28px;"><?php if($key==1){echo "KD1 - A&A";} else { echo "KD".$key;  }?></label>
                       </div></a>
                       
                       <?php if($key==1){ 
                       $L2_1=isset($data[$key]['Level-1'][0]['level2'])?explode(",", $data[$key]['Level-1'][0]['level2']):array();
                       $L2_2=isset($data[$key]['Level-2'][0]['level2'])?explode(",", $data[$key]['Level-2'][0]['level2']):array();
                       $L2_3=isset($data[$key]['Level-3'][0]['level2'])?explode(",", $data[$key]['Level-3'][0]['level2']):array();
                       //echo"<pre>";
                       $final_values=array_merge($L2_1,$L2_2,$L2_3);
                       
                       $final_values_count=array_count_values($final_values);
                       $totalKpaRating2=(isset($final_values_count['Level-1'])?$final_values_count['Level-1']:0) + (isset($final_values_count['Level-2'])?$final_values_count['Level-2']:0) + (isset($final_values_count['Level-3'])?$final_values_count['Level-3']:0);
                       
                       $pcLevel11 = isset($final_values_count['Level-1'])?round(($final_values_count['Level-1']/$totalKpaRating2)*100,2):0;
                       $pcLevel12 = isset($final_values_count['Level-2'])?round(($final_values_count['Level-2']/$totalKpaRating2)*100,2):0;
                       $pcLevel13 = isset($final_values_count['Level-3'])?round(($final_values_count['Level-3']/$totalKpaRating2)*100,2):0;
                       
                       ?>
                           <a href="#<?php echo $key ?>_2"> <div class="lineBar cb" style="height:211px;width:7%;margin:0 2%;" onclick="clusterReport('level2',<?php echo $key; ?>,'<?php echo $block;?>')">
                           <div style="height:<?php echo round(2.11*$pcLevel13,2)."px";?>;background-color:#38c176"><span><?php echo $pcLevel13>0?$pcLevel13:'';?></span></div>
                           <div style="height:<?php echo round(2.11*$pcLevel12,2)."px";?>;background-color:#d2a828"><span><?php echo $pcLevel12>0?$pcLevel12:'';?></span></div>
                           <div style="height:<?php echo round(2.11*$pcLevel11,2)."px";?>;background-color:#ff4c4c"><span><?php echo $pcLevel11>0?$pcLevel11:'';?></span></div>
                           <label class="lineLbl" style="left:-28px;">KD1 - Q&U</label>
                           </div></a>

                       
                        <?php }?>
                       
                           <?php }?>

               </div>
           </div>

<?php } else{?>
Data is not available
<?php } ?>