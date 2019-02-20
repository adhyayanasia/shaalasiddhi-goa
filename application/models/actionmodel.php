<?php

/**
 * Reasons: For fetch  Action Plan's Related Data
 * Copyright (c) Adhyayan services private limited, Inc. and its affiliates.
 * This source code is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE license found in the
 * LICENSE file in the root directory of this source tree.
 */
class actionModel extends Model {
    /*     * Fetch and show list of all  action plan 1
     * $assessor_key_notes_id_c:key note id,
     * $current_stackholder:stack hoder
     * $current_impact:impact row details
     * $currentfrom_date: start from date
     * $currentto_date: end date for action plan
     */

    function addaction1($assessor_key_notes_id_c, $current_stackholder, $current_impact, $currentfrom_date, $currentto_date, $currentleader, $currentfrequency_report, $currentreporting_authority, $action_status, $createdBy, $current_id, $already_ids) {

        $sql = "select * from h_assessor_action1 where assessor_key_notes_id=?";
        $res = $this->db->get_results($sql, array($assessor_key_notes_id_c));
        $res ? $res : array();

        if (count($res) > 0) {
            $res = $res[0];
            if ($res['action_status'] == 0 || $assessor_key_notes_id_c == $current_id) {

                if ($assessor_key_notes_id_c == $current_id) {
                    $action_status = $action_status;
                } else {
                    $action_status = $res['action_status'];
                }

                if ($this->db->update("h_assessor_action1", array("assessor_key_notes_id" => $assessor_key_notes_id_c, "from_date" => $currentfrom_date, "to_date" => $currentto_date, "leader" => (!empty($currentleader) ? $currentleader : NULL), "frequency_report" => (!empty($currentfrequency_report) ? $currentfrequency_report : NULL), "reporting_authority" => $currentreporting_authority, "action_status" => $action_status, "modifyDate" => date("Y-m-d H:i:s"), "modifiedBy" => $createdBy), array("h_assessor_action1_id" => $res['h_assessor_action1_id']))) {
                    $querydel = "delete from h_assessor_action1_impact where assessor_action1_id='" . $res['h_assessor_action1_id'] . "' && assessor_action1_impact_id NOT IN (" . (implode(",", $already_ids)) . ")";
                    if (!$this->db->query($querydel, array())) {
                        return false;
                    }

                    foreach ($current_stackholder as $s_key => $sval) {

                        if (!empty($sval)) {
                            $impact_statement = $current_impact[$s_key];
                            $already_id = $already_ids[$s_key];

                            if (!empty($already_id) && $already_id > 0) {

                                if ($this->db->update("h_assessor_action1_impact", array("assessor_action1_id" => $res['h_assessor_action1_id'], "designation_id" => $sval, "impact_statement" => $impact_statement), array("assessor_action1_impact_id" => $already_id))) {
                                    
                                } else {
                                    return false;
                                }
                            } else {

                                if ($this->db->insert("h_assessor_action1_impact", array("assessor_action1_id" => $res['h_assessor_action1_id'], "designation_id" => $sval, "impact_statement" => $impact_statement))) {
                                    
                                } else {

                                    return false;
                                }
                            }
                        }
                    }
                } else {
                    return false;
                }
            }
        } else {


            if ($this->db->insert("h_assessor_action1", array("assessor_key_notes_id" => $assessor_key_notes_id_c, "from_date" => $currentfrom_date, "to_date" => $currentto_date, "leader" => (!empty($currentleader) ? $currentleader : NULL), "frequency_report" => (!empty($currentfrequency_report) ? $currentfrequency_report : NULL), "reporting_authority" => $currentreporting_authority, "action_status" => $action_status, "createDate" => date("Y-m-d H:i:s"), "createdBy" => $createdBy))) {
                $last_id = $this->db->get_last_insert_id();

                foreach ($current_stackholder as $s_key => $sval) {
                    if (!empty($sval)) {
                        $impact_statement = $current_impact[$s_key];

                        if ($this->db->insert("h_assessor_action1_impact", array("assessor_action1_id" => $last_id, "designation_id" => $sval, "impact_statement" => $impact_statement))) {
                            
                        } else {

                            return false;
                        }
                    }
                }
            } else {
                return false;
            }
        }

        return true;
    }

    /*     * Fetch and show list of all  kpa and judgement statements
     * $assessment_id:id of assessment,
     * $status:status flag
     */

    function updateOverallstatus($assessment_id, $status) {

        if ($this->db->update("h_assessment_user", array("action_planning_status" => $status), array("assessment_id" => $assessment_id, "role" => 3))) {
            return true;
        }

        return false;
    }

    /*     * Save action plan 1st step
     * $assessment_id:id of assessment,
     * $data_array:array of all data
     */

    function addactionnew1($assessment_id, $data_array) {

        if ($this->db->insert("assessor_key_notes", array("text_data" => $data_array['text_data'], "kpa_instance_id" => $data_array['kpa_instance_id'], "assessment_id" => $assessment_id, "type" => "recommendation", "recommendation_id" => $data_array['recommendation_id'], "rec_type" => 1))) {
            $last_id = $this->db->get_last_insert_id();

            if (!$this->db->insert("h_assessor_key_notes_js", array("assessor_key_notes_id" => $last_id, "rec_judgement_instance_id" => $data_array['rec_judgement_instance_id']))) {
                return false;
            }
        } else {
            return false;
        }

        return true;
    }

    /*     * Action plan add for pop
     * $assessment_id,: Id of assessment
     * $data_array: Array of data
     */

    function addactionplan($assessment_id, $data_array) {

        if ($this->db->insert("assessor_key_notes", array("text_data" => $data_array['text_data'], "kpa_instance_id" => $data_array['kpa_instance_id'], "assessment_id" => $assessment_id, "type" => "recommendation", "rec_type" => 1))) {
            $last_id = $this->db->get_last_insert_id();

            if (!$this->db->insert("h_assessor_key_notes_js", array("assessor_key_notes_id" => $last_id, "rec_judgement_instance_id" => $data_array['rec_judgement_instance_id']))) {
                return false;
            }
        } else {
            return false;
        }

        return true;
    }

    /*     * fetch recomodation
     * $recommendation_id:id of recommendation
     */

    function getRecommendationtext($recommendation_id) {
        $sql = "select hlt.translation_text as recommendation_text from d_recommendation a 
                 inner join h_lang_translation hlt on a.equivalence_id = hlt.equivalence_id 
                 where a.recommendation_id=?";
        $res = $this->db->get_row($sql, array($recommendation_id));
        return $res ? $res : array();
    }

    /*     * Action plan add for pop
     * $assessment_id,: Id of assessment
     * $lang_id: language id
     */

    function getKpasForAssessmentNew($assessment_id, $lang_id = DEFAULT_LANGUAGE) {


        $sql = "SELECT hlt.translation_text kpa_name,k.kpa_id,kd.kpa_instance_id
			FROM `d_kpa` k
			inner join h_kpa_diagnostic kd on k.kpa_id=kd.kpa_id
			inner join d_assessment a on kd.diagnostic_id=a.diagnostic_id
			inner join h_assessment_user au on au.assessment_id=a.assessment_id && au.role=3
			inner join h_lang_translation hlt on k.equivalence_id = hlt.equivalence_id
                        
                        inner join h_kpa_kq kkq on kd.kpa_instance_id=kkq.kpa_instance_id
                        inner join h_kq_cq kqcq on kqcq.key_question_instance_id=kkq.key_question_instance_id
                        inner join h_cq_js_instance hcqjs on hcqjs.core_question_instance_id=kqcq.core_question_instance_id && hcqjs.judgement_statement_instance_id NOT IN (select h_assessor_key_notes_js.rec_judgement_instance_id from assessor_key_notes left join h_assessor_key_notes_js on assessor_key_notes.id=h_assessor_key_notes_js.assessor_key_notes_id  where assessor_key_notes.assessment_id=? && assessor_key_notes.type='recommendation' &&  assessor_key_notes.rec_type=1)
                        inner join d_judgement_statement js on js.judgement_statement_id=hcqjs.judgement_statement_id
			
                        
			where a.assessment_id=? and au.role=3 and hlt.translation_type_id=1 and hlt.language_id=?";

        $sqlArgs = array($assessment_id, $assessment_id, $lang_id);

        $sql .= " group by k.kpa_id order by kd.`kpa_order` asc;";

        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    /*     * fetch all key questions for kpa
     * $assessment_id,: Id of assessment
     * $kpa_id: kpa's Id
     * $lang_id: language id
     */

    function getKeyQuestionsForAssessment($assessment_id, $kpa_id = 0, $lang_id = DEFAULT_LANGUAGE) {
        $sql = "SELECT kq.key_question_id, hlt.translation_text key_question_text,kkq.key_question_instance_id
			FROM `d_key_question` kq
			inner join h_kpa_kq kkq on kkq.key_question_id=kq.key_question_id
			inner join h_kpa_diagnostic kd on kd.kpa_instance_id=kkq.kpa_instance_id
                        
                        inner join h_kq_cq kqcq on kqcq.key_question_instance_id=kkq.key_question_instance_id
                        inner join h_cq_js_instance hcqjs on hcqjs.core_question_instance_id=kqcq.core_question_instance_id
                                                
                        inner join d_judgement_statement js on js.judgement_statement_id=hcqjs.judgement_statement_id
			inner join h_jstatement_recommendation hjr on js.judgement_statement_id=hjr.judgement_statement_id and hjr.isActive=1
                        
			inner join d_assessment a on kd.diagnostic_id=a.diagnostic_id
			inner join h_assessment_user au on au.assessment_id=a.assessment_id && au.role=3
			inner join h_lang_translation hlt on kq.equivalence_id = hlt.equivalence_id
                        
                        inner join f_score fs on fs.assessor_id=au.user_id and a.assessment_id=fs.assessment_id and fs.judgement_statement_instance_id=hcqjs.judgement_statement_instance_id && fs.isFinal=1 && hjr.rating_id=fs.rating_id
                        
			where hjr.rating_id IN (" . RATINGS . ") and hjr.recommendation_id NOT IN (select recommendation_id from assessor_key_notes where assessment_id=? && type='recommendation' &&  rec_type=1) and a.assessment_id=?  and au.role=3 and hlt.translation_type_id=2 and hlt.language_id=?";

        $sqlArgs = array($assessment_id, $assessment_id, $lang_id);

        if ($kpa_id > 0) {
            $sql .= " and kd.kpa_instance_id=?";
            $sqlArgs[] = $kpa_id;
        }

        $sql .= " group by kq.key_question_id order by kkq.`kq_order` asc;";
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    /*     * fetch all core question for keyquestion and assessment
     * $assessment_id,: Id of assessment
     * $key_question_instance_id: key question Id
     * $lang_id: language id
     */

    function getCoreQuestionsForKQAssessment($assessment_id, $key_question_instance_id = 0, $lang_id = DEFAULT_LANGUAGE) {
        $sql = "SELECT cq.core_question_id,hlt.translation_text as core_question_text,kqcq.core_question_instance_id
			FROM `d_core_question` cq
                        inner join h_lang_translation hlt on cq.equivalence_id = hlt.equivalence_id
			inner join h_kq_cq kqcq on kqcq.core_question_id=cq.core_question_id
			inner join h_kpa_kq kkq on kkq.key_question_instance_id=kqcq.key_question_instance_id
			inner join h_kpa_diagnostic kd on kd.kpa_instance_id=kkq.kpa_instance_id
                        inner join h_cq_js_instance hcqjs on hcqjs.core_question_instance_id=kqcq.core_question_instance_id
                        
                        inner join d_judgement_statement js on js.judgement_statement_id=hcqjs.judgement_statement_id
			inner join h_jstatement_recommendation hjr on js.judgement_statement_id=hjr.judgement_statement_id and hjr.isActive=1
                         
			inner join d_assessment a on kd.diagnostic_id=a.diagnostic_id
			inner join h_assessment_user au on au.assessment_id=a.assessment_id  && au.role=3
                        
                        inner join f_score fs on fs.assessor_id=au.user_id and a.assessment_id=fs.assessment_id and fs.judgement_statement_instance_id=hcqjs.judgement_statement_instance_id && fs.isFinal=1 && hjr.rating_id=fs.rating_id
                       
			where hjr.rating_id IN (" . RATINGS . ") and hjr.recommendation_id NOT IN (select recommendation_id from assessor_key_notes where assessment_id=? && type='recommendation' &&  rec_type=1) and  a.assessment_id=? and au.role=3  and hlt.language_id=?";


        $sqlArgs = array($assessment_id, $assessment_id, $lang_id);
        if ($key_question_instance_id > 0) {
            $sql .= " and kkq.key_question_instance_id=?";
            $sqlArgs[] = $key_question_instance_id;
        }

        $sql .= " group by cq.core_question_id order by kqcq.`cq_order` asc;";
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    /*     * fetch all judgement statement for core
     * $assessment_id,: Id of assessment
     * $core_question_instance_id: corequestion's Id
     * $lang_id: language id
     */

    function getJSForCQAssessment($assessment_id, $core_question_instance_id = 0, $lang_id = DEFAULT_LANGUAGE) {
        $sql = "SELECT js.judgement_statement_id,hlt.translation_text as judgement_statement_text,hcqjs.judgement_statement_instance_id
			FROM `d_judgement_statement` js
                        inner join h_lang_translation hlt on js.equivalence_id = hlt.equivalence_id
                        inner join h_cq_js_instance hcqjs on js.judgement_statement_id=hcqjs.judgement_statement_id
                        inner join h_kq_cq kqcq on kqcq.core_question_instance_id=hcqjs.core_question_instance_id
			inner join h_kpa_kq kkq on kkq.key_question_instance_id=kqcq.key_question_instance_id
			inner join h_kpa_diagnostic kd on kd.kpa_instance_id=kkq.kpa_instance_id
                        
                        inner join h_jstatement_recommendation hjr on js.judgement_statement_id=hjr.judgement_statement_id and hjr.isActive=1
                        
			inner join d_assessment a on kd.diagnostic_id=a.diagnostic_id
			inner join h_assessment_user au on au.assessment_id=a.assessment_id  && au.role=3
                        
                        inner join f_score fs on fs.assessor_id=au.user_id and a.assessment_id=fs.assessment_id and fs.judgement_statement_instance_id=hcqjs.judgement_statement_instance_id && fs.isFinal=1 && hjr.rating_id=fs.rating_id
                       
			where hjr.rating_id IN (" . RATINGS . ") and hjr.recommendation_id NOT IN (select recommendation_id from assessor_key_notes where assessment_id=? && type='recommendation' &&  rec_type=1) and a.assessment_id=? and au.role=3  and hlt.language_id=?";

        $sqlArgs = array($assessment_id, $assessment_id, $lang_id);
        if ($core_question_instance_id > 0) {
            $sql .= " and kqcq.core_question_instance_id=?";
            $sqlArgs[] = $core_question_instance_id;
        }
        $sql .= " group by js.judgement_statement_id order by hcqjs.`js_order` asc;";
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }

    /*     * fetch all judgement statement for kpa
     * $assessment_id,: Id of assessment
     * $core_question_instance_id: corequestion's Id
     * $lang_id: language id
     */

    function getJSForKpaAssessment($assessment_id, $kpa_id, $lang_id = DEFAULT_LANGUAGE) {
        $sql = "select  hltjs.translation_text as judgement_statement_text, a.judgement_statement_instance_id
from f_score a
inner join h_cq_js_instance b on a.judgement_statement_instance_id=b.judgement_statement_instance_id
INNER JOIN d_judgement_statement js ON js.judgement_statement_id = b.judgement_statement_id
INNER JOIN h_lang_translation hltjs ON js.equivalence_id = hltjs.equivalence_id and hltjs.language_id=?              
inner join h_kq_cq c on b.core_question_instance_id=c.core_question_instance_id
inner join h_kpa_kq d on c.key_question_instance_id=d.key_question_instance_id
inner join h_kpa_diagnostic e on d.kpa_instance_id=e.kpa_instance_id
inner join d_assessment f on a.assessment_id=f.assessment_id
inner join h_assessment_user s on a.assessment_id=s.assessment_id and a.assessor_id=s.user_id and role=3 and s.isFilled =1 and s.percComplete=100.00 where  a.assessment_id=?  and  d.kpa_instance_id = ? && a.judgement_statement_instance_id NOT IN (select h_assessor_key_notes_js.rec_judgement_instance_id from assessor_key_notes left join h_assessor_key_notes_js on assessor_key_notes.id=h_assessor_key_notes_js.assessor_key_notes_id  where assessor_key_notes.assessment_id=? && assessor_key_notes.type='recommendation' &&  assessor_key_notes.rec_type=1)";

        $sqlArgs = array($lang_id, $assessment_id, $kpa_id, $assessment_id);

        $sql .= " group by js.judgement_statement_id order by b.`js_order` asc;";
        $res = $this->db->get_results($sql, $sqlArgs);
        return $res ? $res : array();
    }


    /*     * Fetch recomendation row
     * $rec_id,: recomendation id
     */

    function getrowdetails($rec_id) {

        $sql = "select * from h_assessor_action1 where assessor_key_notes_id=?";
        $res = $this->db->get_row($sql, array($rec_id));
        $res ? $res : array();
    }

    /*     * Fetch recomendation row for assessment
     * $rec_id,: recomendation id
     * $assessment_id,: Id of assessment
     */

    function deleteRec($assessment_id, $rec_id) {

        $sql = "select * from h_assessor_action1 where assessor_key_notes_id=?";
        $res = $this->db->get_results($sql, array($rec_id));
        $res ? $res : array();

        if (count($res) > 0) {

            $res = $res[0];
            if (!$this->db->delete('h_assessor_action1_impact', array("assessor_action1_id" => $res['h_assessor_action1_id']))) {
                return false;
            }

            if (!$this->db->delete('h_assessor_action1', array("assessor_key_notes_id" => $rec_id))) {
                return false;
            }
        }

        if (!$this->db->delete('h_assessor_key_notes_js', array("assessor_key_notes_id" => $rec_id))) {
            return false;
        }

        if (!$this->db->delete('assessor_key_notes', array("id" => $rec_id))) {
            return false;
        }

        return true;
    }

    /*     * remove team member for action plan
     * $rec_id,: recomendation id
     * $assessment_id,: Id of assessment
     */

    function deleteTeamAction2($h_assessor_action1_id) {
        if (!$this->db->delete('h_review_action2_team', array("h_assessor_action1_id" => $h_assessor_action1_id))) {
            return false;
        }
        return true;
    }

    //add team members for action plan
    function addTeamAction2($h_assessor_action1_id, $team_designation, $team_member_name, $createdBy) {

        if (!$this->db->insert("h_review_action2_team", array("h_assessor_action1_id" => $h_assessor_action1_id, "team_designation" => $team_designation, "team_member_name" => $team_member_name, "createDate" => date("Y-m-d H:i:s"), "createdBy" => $createdBy))) {

            return false;
        }

        return true;
    }

    //delete activity details from array of action plan
    function deleteActionActivity2($h_assessor_action1_id, $idsleft = array()) {
        if (count($idsleft) > 0) {

            $ids = implode(",", $idsleft);

            $queryS = "select GROUP_CONCAT(h_review_action2_activity_id) as h_review_action2_activity_id from h_review_action2_activity where h_assessor_action1_id=? && h_review_action2_activity_id NOT IN (" . $ids . ") group by h_assessor_action1_id";
            $resS = $this->db->get_row($queryS, array($h_assessor_action1_id));

            if (isset($resS['h_review_action2_activity_id']) && explode(",", $resS['h_review_action2_activity_id']) > 0) {

                $querydel = "delete from h_review_action2_activity_stackholder where h_review_action2_activity_id IN (" . $resS['h_review_action2_activity_id'] . ")";

                if (!$this->db->query($querydel, array())) {
                    return false;
                }
            }

            $query = "delete from h_review_action2_activity where h_assessor_action1_id=? && h_review_action2_activity_id NOT IN (" . $ids . ")";
            if (!$this->db->query($query, array($h_assessor_action1_id))) {
                return false;
            }
        } else {

            $queryS = "select GROUP_CONCAT(h_review_action2_activity_id) as h_review_action2_activity_id from h_review_action2_activity where h_assessor_action1_id=? group by h_assessor_action1_id";
            $resS = $this->db->get_row($queryS, array($h_assessor_action1_id));

            if (isset($resS['h_review_action2_activity_id']) && explode(",", $resS['h_review_action2_activity_id']) > 0) {
                $querydel = "delete from h_review_action2_activity_stackholder where h_review_action2_activity_id IN (" . $resS['h_review_action2_activity_id'] . ")";

                if (!$this->db->query($querydel, array())) {
                    return false;
                }
            }

            if (!$this->db->delete('h_review_action2_activity', array("h_assessor_action1_id" => $h_assessor_action1_id))) {
                return false;
            }
        }

        return true;
    }

    //Add acitity data for action plan
    function addActionActivity2($h_assessor_action1_id, $activity_stackholder, $activity, $activity_details, $activity_status, $activity_date, $activity_actual_date, $activity_comments, $createdBy, $old_id) {

        if (empty($old_id)) {
            if (!$this->db->insert("h_review_action2_activity", array("h_assessor_action1_id" => $h_assessor_action1_id, "activity" => $activity, "activity_details" => $activity_details, "activity_status" => $activity_status, "activity_date" => $activity_date, "activity_actual_date" => $activity_actual_date, "activity_comments" => $activity_comments, "createDate" => date("Y-m-d H:i:s"), "createdBy" => $createdBy))) {

                return false;
            }

            $last_id = $this->db->get_last_insert_id();

            foreach ($activity_stackholder as $keys => $vals) {

                if (!$this->db->insert("h_review_action2_activity_stackholder", array("h_review_action2_activity_id" => $last_id, "activity_stackholder" => $vals))) {

                    return false;
                }
            }
        } else {

            $queryS = "select * from h_review_action2_activity where h_review_action2_activity_id=?";
            $resS = $this->db->get_row($queryS, array($old_id));
            $cacomments = $activity_comments;
            $caactivity_status = $activity_status;
            if ($activity_status == 3) {
                $activity_status = 0;
                $activity_comments = $resS['activity_comments'];
                $caactivity_status = 3;
            }

            $querySS = "select * from h_review_action2_activity_stackholder where h_review_action2_activity_id=?";
            $resSS = $this->db->get_results($querySS, array($old_id));

            if (!$this->db->update("h_review_action2_activity", array("h_assessor_action1_id" => $h_assessor_action1_id, "activity" => $activity, "activity_details" => $activity_details, "activity_status" => $activity_status, "activity_date" => $activity_date, "activity_actual_date" => $activity_actual_date, "activity_comments" => $activity_comments, "modifyDate" => date("Y-m-d H:i:s"), "modifiedBy" => $createdBy), array("h_review_action2_activity_id" => $old_id))) {

                return false;
            }

            if (!$this->db->delete('h_review_action2_activity_stackholder', array("h_review_action2_activity_id" => $old_id))) {
                return false;
            }

            foreach ($activity_stackholder as $keys => $vals) {

                if (!$this->db->insert("h_review_action2_activity_stackholder", array("h_review_action2_activity_id" => $old_id, "activity_stackholder" => $vals))) {

                    return false;
                }
            }

            if ($caactivity_status == 3) {
                if (!$this->db->insert("h_review_action2_activity_postponed", array("h_review_action2_activity_id" => $resS['h_review_action2_activity_id'], "h_assessor_action1_id" => $resS['h_assessor_action1_id'], "activity" => $resS['activity'], "activity_details" => $resS['activity_details'], "activity_status" => 3, "activity_date" => $resS['activity_date'], "activity_actual_date" => $resS['activity_actual_date'], "activity_comments" => $cacomments, "createDate" => date("Y-m-d H:i:s"), "createdBy" => $createdBy))) {

                    return false;
                }
                $last_id1 = $this->db->get_last_insert_id();
                foreach ($resSS as $keys => $vals) {

                    if (!$this->db->insert("h_review_action2_activity_stackholder_postponed", array("h_review_action2_activity_postponed_id" => $last_id1, "activity_stackholder" => $vals['activity_stackholder']))) {

                        return false;
                    }
                }
            }
        }

        return true;
    }

    /*     * Fetch detail of of assessor for action plan report
     * $id_c: Id of accessor key note
     * $lang_id : language id
     */

    function getDetailsofAssessment($id_c, $lang_id = DEFAULT_LANGUAGE) {

        $sql = "select haa1.h_assessor_action1_id,hlt.translation_text,haa1.action_status,haa1.from_date,haa1.to_date,df.frequecy_text,df.frequency_days,akn.text_data,du.name,school_aqs_pref_start_date,school_aqs_pref_end_date from (select * from h_assessor_action1 where assessor_key_notes_id=?) haa1
                 inner join assessor_key_notes akn on haa1.assessor_key_notes_id=akn.id
                 inner join h_kpa_diagnostic hkd on hkd.kpa_instance_id=akn.kpa_instance_id
                 inner join d_kpa dk on dk.kpa_id=hkd.kpa_id
                 inner join h_lang_translation  hlt on hlt.equivalence_id=dk.equivalence_id && language_id=" . $lang_id . "
                 inner join d_assessment da on da.assessment_id=akn.assessment_id
                 inner join d_AQS_data dAQS on dAQS.id=da.aqsdata_id
                 inner join d_user du on du.user_id=haa1.leader
                 inner join d_frequency df on df.frequency_id=haa1.frequency_report
                 ";

        $res = $this->db->get_row($sql, array($id_c));

        $sql1 = "select hlt.translation_text,evidence_text_lw,evidence_text_co,evidence_text_in,evidence_text_bl,group_concat(df.file_name SEPARATOR '-@$#@$-') as files  from (select * from h_assessor_action1 where assessor_key_notes_id=?) haa1
                 inner join assessor_key_notes akn on haa1.assessor_key_notes_id=akn.id
                 inner join h_assessor_key_notes_js haknj on haknj.assessor_key_notes_id=akn.id
                 inner join h_cq_js_instance h_cq_i on h_cq_i.judgement_statement_instance_id=haknj.rec_judgement_instance_id
                 inner join d_judgement_statement djs on djs.judgement_statement_id=h_cq_i.judgement_statement_id
                 inner join h_lang_translation  hlt on hlt.equivalence_id=djs.equivalence_id && language_id=" . $lang_id . "
                 inner join d_assessment da on da.assessment_id=akn.assessment_id
                 inner join h_assessment_user hau on hau.assessment_id=da.assessment_id && role=4
                 inner join f_score fs on fs.assessment_id=hau.assessment_id && fs.assessor_id=hau.user_id && isFinal=1 && fs.judgement_statement_instance_id=haknj.rec_judgement_instance_id
                 left join h_score_file hsf on hsf.score_id=fs.score_id
                 left join d_file df on df.file_id=hsf.file_id 
                 group by haknj.h_assessor_key_notes_js_id
                 ";
        $res1 = $this->db->get_results($sql1, array($id_c));
        $res1 ? $res1 : array();
        $res['js_evidences'] = $res1;

        return $res ? $res : array();
    }

    /*     * Fetch recomendation row for assessment
     * $rec_id,: recomendation id
     * $id_c: Id of recomendation
     * $lang_id : language id
     */

    function getDetailsofImpactStmnt($id_c, $lang_id = DEFAULT_LANGUAGE) {

        $sql = "SELECT d.designation,im.impact_statement,im.assessor_action1_impact_id,im.assessor_action1_id,im.designation_id FROM h_assessor_action1_impact im
                 INNER JOIN h_assessor_action1 a on a.h_assessor_action1_id=im.assessor_action1_id
                 INNER JOIN d_designation d on d.designation_id=im.designation_id
                 
                 WHERE a.assessor_key_notes_id = ?
                 ";

        $res = $this->db->get_results($sql, array($id_c));
        return $res ? $res : array();
    }

    //change status of action plan
    function chngeActionActivity2Status($h_assessor_action1_id) {

        return $this->db->update('h_assessor_action1', array('action_status' => 2), array('h_assessor_action1_id' => $h_assessor_action1_id));
    }

    /*     * Fetch recomendation row for assessment
     * $rec_id,: recomendation id
     * $id_c: Id of recomendation
     * $assessment_id,: Id of assessment
     * $lang_id : language id
     */

    function getimpactStmntData($id_c, $assessment_id, $lang_id = DEFAULT_LANGUAGE) {

        $sql = "SELECT ic.comments,ic.class_id,st.designation_id,st.comments as stk_comments,im.activity_method_id,im.date,im.comments as im_comments,im.statement_id
             ,group_concat(concat(fs.file_id,'|',f.file_name)SEPARATOR '||') as files FROM h_impact_statement im
                 LEFT JOIN h_impact_statement_classes ic  on ic.impact_statement_id=im.id
                 LEFT JOIN h_impact_statement_stakeholders st  on st.impact_statement_id=im.id
                 LEFT JOIN h_impact_statement_files fs  on fs.impact_statement_id=im.id 
                 LEFT JOIN d_file f on f.file_id = fs.file_id
                 
                 WHERE im.assessment_id = ? AND im.action_plan_id = ?  group by im.id";
        $res = $this->db->get_results($sql, array($assessment_id, $id_c));
        return $res ? $res : array();
    }

    //actiona plan's team member
    function getTeamAction2($h_review_action2_team) {

        $sql = "select * from h_review_action2_team where h_assessor_action1_id=?";
        $res = $this->db->get_results($sql, array($h_review_action2_team));
        return $res ? $res : array();
    }

    //acitivity row for team
    function getActivityAction2($h_review_action2_team) {

        $sql = "select a.*,GROUP_CONCAT(DISTINCT h_review_action2_activity_postponed_id) as postponed_ids ,GROUP_CONCAT(DISTINCT c.activity_stackholder) as activity_stackholder_ids
                from h_review_action2_activity a
                left join h_review_action2_activity_stackholder c on a.h_review_action2_activity_id=c.h_review_action2_activity_id
                left join h_review_action2_activity_postponed b on a.h_review_action2_activity_id=b.h_review_action2_activity_id 
                
                where a.h_assessor_action1_id=? group by a.h_review_action2_activity_id";

        $res = $this->db->get_results($sql, array($h_review_action2_team));

        return $res ? $res : array();
    }

    //Impact list 
    function getImpactMethod() {

        $sql = "select * FROM d_impact_method";

        $res = $this->db->get_results($sql);

        return $res ? $res : array();
    }

    //get activity tips
    function getActivityActionTip2($h_review_action2_team, $date, $activity, $status) {

        $statusf = "";
        if ($status == "C") {
            $statusf = " && a.activity_status=2";
        } else if ($status == "P") {
            $statusf = " && (a.activity_status=1 || a.activity_status=0) && a.activity_date>='" . date("Y-m-d") . "' ";
        } else if ($status == "Ex") {
            $statusf = " && (a.activity_status=1 || a.activity_status=0) && a.activity_date<'" . date("Y-m-d") . "' ";
        }

        $sql = "select a.*,GROUP_CONCAT(DISTINCT dd.designation  SEPARATOR ',') as activity_stackholder_ids
                from h_review_action2_activity a
                left join h_review_action2_activity_stackholder c on a.h_review_action2_activity_id=c.h_review_action2_activity_id
                left join d_designation dd on dd.designation_id=c.activity_stackholder
                left join d_activity da on da.activity_id=a.activity
                
                where a.h_assessor_action1_id=? && da.activity=? && a.activity_date=? " . $statusf . " group by a.h_review_action2_activity_id";


        $res = $this->db->get_results($sql, array($h_review_action2_team, $activity, $date));

        return $res ? $res : array();
    }

    //get row of postpond activity
    function getActivityPostponedAction2($h_review_action2_team) {


        $sql = "select a.*,GROUP_CONCAT(DISTINCT dd.designation) as designation,da.activity from h_review_action2_activity_postponed a 
                
                INNER JOIN d_activity da on da.activity_id =a.activity
                INNER JOIN h_review_action2_activity_stackholder_postponed p on a.h_review_action2_activity_postponed_id=p.h_review_action2_activity_postponed_id
                INNER JOIN d_designation dd on p.activity_stackholder =dd.designation_id
                where a.h_review_action2_activity_id=? GROUP BY a.h_review_action2_activity_postponed_id order by a.activity_date desc";

        $res = $this->db->get_results($sql, array($h_review_action2_team));
        return $res ? $res : array();
    }

    //Delete Impact data from array
    function deleteImpactStatement($assessment_id, $action_plan_id) {

        $sql = "select id from h_impact_statement where assessment_id=? and action_plan_id=?";
        $res = $this->db->get_results($sql, array($assessment_id, $action_plan_id));
        $idToBeDeleted = $res ? array_column($res, "id") : array();
        $delIs = '';
        if (!empty($idToBeDeleted)) {
            $comaSepratedIds = implode(",", $idToBeDeleted);
            $impactDelQuery = "DELETE FROM h_impact_statement WHERE id IN ($comaSepratedIds)";
            $stakeDelQuery = "DELETE FROM h_impact_statement_stakeholders WHERE impact_statement_id IN ($comaSepratedIds)";
            $classDelQuery = "DELETE FROM h_impact_statement_classes WHERE impact_statement_id IN ($comaSepratedIds)";
            $imageDelQuery = "DELETE FROM h_impact_statement_files WHERE impact_statement_id IN ($comaSepratedIds)";
            $delIs = $this->db->query($impactDelQuery);
            $delSt = $this->db->query($stakeDelQuery);
            $delCl = $this->db->query($classDelQuery);
            $delImg = $this->db->query($imageDelQuery);
        }
        if ($delIs) {
            return true;
        } else
            return false;
    }

    //insert impact row for action plan
    function addImpactStatement($paramsData, $files = array()) {

        $last_impact_insert_id = 0;
        $last_stake_insert_id = 0;
        $last_class_insert_id = 0;
        $sql = "insert into  h_impact_statement (activity_method_id,date,comments,assessment_id,action_plan_id,statement_id,row_id) values ";

        $sqlStakeholders = "insert into  h_impact_statement_stakeholders (impact_statement_id,designation_id,comments) values ";
        $sqlClassOption = "insert into  h_impact_statement_classes (impact_statement_id,class_id,comments) values ";
        if (!empty($paramsData)) {
            foreach ($paramsData as $key => $val) {

                $data = array();
                $sqlStakeholdersParams = '';
                $sqlClassOptionParams = '';
                $sqlParams = "(?,?,?,?,?,?,?),";
                $data[] = $val['activity_method_id'];

                $data[] = date("Y-m-d", strtotime($val['date']));
                if (!($val['activity_method_id'] == 4 || $val['activity_method_id'] == 2)) {

                    $data[] = $val['comments'];
                } else {
                    $data[] = 0;
                }
                $data[] = $val['assessment_id'];
                $data[] = $val['action_plan_id'];
                $data[] = $val['statement_id'];
                $data[] = $val['row_id'];
                $sqlParams = trim($sqlParams, ",");

                $this->db->query($sql . $sqlParams, $data);
                $last_id = $this->db->get_last_insert_id();
                if ($last_id > 0 && !empty($files[$val['statement_id']][$val['row_id']])) {
                    $file_insert_status = $this->addImpactStatementFiles($files[$val['statement_id']][$val['row_id']], $last_id);
                    if (!$file_insert_status) {
                        return false;
                    }
                }
                if ($last_id > 0 && $val['activity_method_id'] == 4) {
                    $sqlStakeholdersParams .= $sqlStakeholders . "(?,?,?)";
                    $last_stake_insert_id = $this->db->query($sqlStakeholdersParams, array($last_id, $val['activity_option_id'], $val['comments']));
                    if (!$last_stake_insert_id) {
                        return false;
                    }
                } else if ($last_id > 0 && $val['activity_method_id'] == 2) {
                    $sqlClassOptionParams = $sqlClassOption . "(?,?,?)";
                    $last_class_insert_id = $this->db->query($sqlClassOptionParams, array($last_id, $val['activity_option_id'], $val['comments']));
                    if (!$last_class_insert_id) {
                        return false;
                    }
                } else if ($last_id < 1) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }

    //Apload files for impact of action plan
    function addImpactStatementFiles($filesData, $impact_statement_id) {

        $data = array();
        $sql = "insert into  h_impact_statement_files (impact_statement_id,file_id) values ";
        if (!empty($filesData)) {
            foreach ($filesData as $key => $val) {
                $sql .= "(?,?),";
                $data[] = $impact_statement_id;
                $data[] = $val;
            }
            $sql = trim($sql, ",");
            return $this->db->query($sql, $data);
        }
        return false;
    }

    //Check Activation date for Impact Statement
    function checkImpactStatementDate($assessmentId, $acId, $startDate, $endDate) {

        $sql = "select id from h_impact_statement where assessment_id= ? AND action_plan_id=? AND date NOT BETWEEN ? AND ? group by action_plan_id";
        $res = $this->db->get_results($sql, array($assessmentId, $acId, $startDate, $endDate));
        return $res ? true : false;
    }

    //check activation date for action plan validation 
    function checkActionActivityDate($assessmentId, $acId, $startDate, $endDate) {

        $sql = "select s.h_assessor_action1_id from h_assessor_action1 a INNER JOIN "
                . " h_review_action2_activity s on a.h_assessor_action1_id = s.h_assessor_action1_id "
                . "where a.assessor_key_notes_id=? AND s.activity_date NOT BETWEEN ? AND ? group by s.h_assessor_action1_id";
        $res = $this->db->get_results($sql, array($acId, $startDate, $endDate));
        return $res ? true : false;
    }

    //function to get action plan leader data to send email
    function getLeaderData($currentleader) {

        $sql = " SELECT name,email FROM d_user WHERE user_id IN ($currentleader) ";
        return $this->db->get_results($sql, array($currentleader));
    }

    //send mail for action planning
    function sendNotificationMail($emailParams, $school_name, $current_id = 0, $assessor_key_notes = array(), $assessment_id = 0) {

        $sqlTemplate = " SELECT u.template_text,q.subject,q.sender,q.sender_name,q.cc FROM d_review_notification_template u "
                . "INNER JOIN h_review_notification_mail_users q on u.id = q.notification_id WHERE u.template_type=? ";
        $emailTemplateData = $this->db->get_row($sqlTemplate, array(22));
        $subject = str_replace('_school_', $school_name, $emailTemplateData['subject']);
        $sender = $emailTemplateData['sender'];
        $sender_name = $emailTemplateData['sender_name'];

        foreach ($emailParams as $data) {

            $toEmail = $data['email'];
            $toName = $data['name'];
            $mail_body = str_replace('_name_', $toName, $emailTemplateData['template_text']);
            $mail_body = str_replace('_school_', $school_name, $mail_body);
            $mail_body = nl2br($mail_body);
            if (sendEmail($sender, $sender_name, $toEmail, $toName, '', '', $subject, $mail_body, '')) {

                if (!empty($current_id)) {

                    $this->db->update("h_assessor_action1", array("mail_status" => 1), array("assessor_key_notes_id" => $current_id));
                } else if (!empty($assessor_key_notes)) {

                    $sqlTemplate = ' UPDATE h_assessor_action1 SET mail_status = ? WHERE  assessor_key_notes_id IN (' . implode(",", $assessor_key_notes) . ") ";
                    $emailTemplateData = $this->db->get_row($sqlTemplate, array(1));
                }
            }
        }
    }

    //Get block/cluster/zone and state list for zone admin 
    function getBlockClusterSchoolAssessments($request) {
        $cond = '';
        if (isset($request['zoneId'])) {
            $cond .= " and z.zone_id=" . $request['zoneId'];
        }
        if (isset($request['clusterId'])) {
            $cond .= " and p.province_id =" . $request['clusterId'];
        }
        if (isset($request['blockId'])) {
            $cond .= " and n.network_id=" . $request['blockId'];
        }
        if (isset($request['schoolId'])) {
            $cond .= " and c.client_id=" . $request['schoolId'];
        }
        if (isset($request['roundId'])) {
            $cond .= " and aqs_round=" . $request['roundId'];
        }
        if (isset($request['assessment_id'])) {
            $cond .= " and g.assessment_id=" . $request['assessment_id'];
        }
        $sql = "SELECT distinct g.assessment_id,z.zone_id,n.network_name,n.network_id as block_id,p.province_name as cluster_name,p.province_id as cluster_id,g.client_id,c.client_name,aqs_round,b.user_id
                  FROM d_state s
                  INNER JOIN h_zone_state zs
                          ON s.state_id = zs.state_id
                  INNER JOIN d_zone z
                          ON z.zone_id = zs.zone_id
                  INNER JOIN h_network_zone_state AS nzs
                          ON nzs.zone_id = z.zone_id
                  INNER JOIN d_network n
                          ON n.network_id = nzs.network_id
                  INNER JOIN h_cluster_block_zone_state cbzs
                          ON cbzs.block_id = n.network_id
                  INNER JOIN d_province p
                          ON p.province_id = cbzs.cluster_id
                  INNER JOIN h_client_province cp
                          ON cp.province_id = p.province_id
                  INNER JOIN d_client c
                          ON c.client_id = cp.client_id
                  INNER JOIN d_assessment g
                          ON g.client_id = c.client_id
                  INNER JOIN h_assessment_user b
                          ON g.assessment_id = b.assessment_id
                    WHERE b.role=4 AND b.isFilled=1  $cond 
                  and  percComplete=100.00";
        $res = $this->db->get_results($sql);
        return $res;
    }

    function getAssessmentNumRound($client_id) {

        $sql = "select distinct aqs_round from d_assessment where client_id=$client_id order by create_date asc";
        $res = $this->db->get_results($sql, array($client_id));
        return $res;
    }

    //Get client Id
    function getclientID($asses_id) {

        $sql = "select client_id from d_assessment where assessment_id='$asses_id'";
        $res = $this->db->get_results($sql);
        return $res;
    }

}
