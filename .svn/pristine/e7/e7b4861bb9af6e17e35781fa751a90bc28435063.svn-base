<?php

class StatModel extends BaseModel {

    public function getProjectAmountRange($amount) {
        if($amount>=0 && $amount<100000) {
            return '10万以下';
        }
        if($amount>=100000 && $amount<200000) {
            return '10-20万';
        }
        if($amount>=200000 && $amount<300000) {
            return '20-30万';
        }
        if($amount>=300000) {
            return '30万以上';
        }
    }

    public function getProjectAmountRangeReverse($range_name) {
        if($range_name=='5万以下（含5万）') {
            return array(1,50000);
        }
        if($range_name=='5万以上') {
            return array(50001,10000000);
        }
    }

    public function getYearRange($year) {
        if(!$year) return;
        $diff = date('Y') - $year;

        if($diff>=0 && $diff<=1) {
            return '1年以下';
        }
        if($diff>1 && $diff<=2) {
            return '1-2年';
        }
        if($diff>2 && $diff<=4) {
            return '2-4年';
        }
        if($diff>4) {
            return '4年以上';
        }
    }

    public function getYearRangeReverse($range_name) {
        $cyear = date('Y');

        if($range_name=='1年以下') {
            return array(strval($cyear-1).'-01', $cyear . '-12');
        }
        if($range_name=='1-2年') {
            return array(strval($cyear-2).'-01', strval($cyear-1).'-12');
        }
        if($range_name=='2-4年') {
            return array(strval($cyear-2).'-01', strval($cyear-2).'-12');
        }
        if($range_name=='4年以上') {
            return array(strval($cyear-100).'-01', strval($cyear-4).'-12');
        }
    }

    // public function getPartnerAssetRange($asset) {
    //     if(!$asset) return;

    //     if($asset>=0 && $asset<=100000) {
    //         return '10万以下';
    //     }
    //     if($asset>100000 && $asset<=500000) {
    //         return '10-50万';
    //     }
    //     if($asset>500000 && $asset<=1000000) {
    //         return '50-100万';
    //     }
    //     if($asset>1000000) {
    //         return '100万以上';
    //     }
    // }

    // public function getPartnerAssetRangeReverse($range_name) {
    //     if($range_name=='10万以下') {
    //         return array(1,99999);
    //     }
    //     if($range_name=='10-50万') {
    //         return array(100000,499999);
    //     }
    //     if($range_name=='50-100万') {
    //         return array(500000,999999);
    //     }
    //     if($range_name=='100万以上') {
    //         return array(1000000,100000000);
    //     }
    // }

}
?>