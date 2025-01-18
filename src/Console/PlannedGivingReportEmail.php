<?php

namespace Bishopm\Churchsite\Console;

use Illuminate\Console\Command;
use Bishopm\Churchsite\Models\Individual;
use Bishopm\Churchsite\Models\User;
use Bishopm\Churchsite\Models\Gift;
use Bishopm\Churchsite\Models\Society;
use DB;
use Bishopm\Churchsite\Mail\GivingMail;
use Bishopm\Churchsite\Mail\SimpleMail;
use Illuminate\Support\Facades\Mail;

class PlannedGivingReportEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'churchsite:givingemails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send giving report by email to planned givers';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $data=array();
        $today=date('Y-m-d');
        $lagtime=intval($society->giving_lag);
        //echo "You have a lag setting of " . $lagtime . " days\n";
        $effdate=strtotime($today)-$lagtime*86400;
        //echo "Effdate: " . date("d M Y", $effdate) . "\n";
        $repyr=date('Y', $effdate);
        //echo "Your report year is " . $repyr . "\n";
        $reportnums=intval($society->giving_reports);
        //echo "Your system will deliver " . $reportnums . " reports per year\n";
        $adminuser=$society->giving_user;
        $administrator=User::find($adminuser)->individual->email;
        switch ($reportnums) {
            case 1:
                $reportdates=array($repyr . "-12-31");
                break;
            case 2:
                $reportdates=array($repyr . "-06-30",$repyr . "-12-31");
                break;
            case 3:
                $reportdates=array($repyr . "-04-30",$repyr . "-08-31",$repyr . "-12-31");
                break;
            case 4:
                $reportdates=array($repyr . "-03-31",$repyr . "-06-30",$repyr . "-09-30",$repyr . "-12-31");
                break;
            case 6:
                $reportdates=array($repyr . "-02-28",$repyr . "-04-30",$repyr . "-06-30",$repyr . "-08-31",$repyr . "-10-31",$repyr . "-12-31");
                break;
            case 12:
                $reportdates=array($repyr . "-01-31",$repyr . "-02-28",$repyr . "-03-31",$repyr . "-04-30",$repyr . "-05-31",$repyr . "-06-30",$repyr . "-07-31",$repyr . "-08-31",$repyr . "-09-30",$repyr . "-10-31",$repyr . "-11-30",$repyr . "-12-31");
                break;
        }
        if (in_array(date("Y-m-d", $effdate), $reportdates)) {
            $period=12/$reportnums;
            $endofperiod=date('Y-m-t', $effdate);
            $startmonth=intval(date('m', $effdate))-$period+1;
            if ($startmonth<1) {
                $startmonth=$startmonth+12;
            }
            if ($startmonth<10) {
                $sm="0" . strval($startmonth);
            } else {
                $sm=strval($startmonth);
            }
            $startofperiod=$repyr . "-" . $sm . "-01";
            // echo "Calculating totals for the period: " . $startofperiod . " to " . $endofperiod . "\n";
            $givers=Individual::insociety($society->id)->where('giving', '>', 0)->where('email', '<>', '')->get();
            $noemailgivers=Individual::insociety($society->id)->where('giving', '>', 0)->where('email', '')->get();
            $msg="Planned giving emails were sent today to " . count($givers) . " planned givers.";
            if (count($noemailgivers)) {
                $msg.="<br><br>The following people are listed as planned givers but do not have email addresses and therefore require a hardcopy report:<br><br>";
                foreach ($noemailgivers as $nomail) {
                    $msg.=$nomail->firstname . " " . $nomail->surname . "<br>";
                }
            } else {
                $msg.="<br><br>Good news! All planned givers at present have email addresses :)";
            }
            $msg.="<br><br>Thank you!";
            $nodat=array();
            $nodat['title']="Planned giving emails sent";
            $nodat['sender']="admin@church.net.za";
            $nodat['society']=$society->society;
            $nodat['website']=$society->website;
            $nodat['body']=$msg;
            Mail::to($administrator)->queue(new SimpleMail($nodat));
            foreach ($givers as $giver) {
                $data[$giver->giving]['email'][]=$giver->email;
                if (count($data[$giver->giving]['email'])==1) {
                    $data[$giver->giving]['period']=$startofperiod . " to " . $endofperiod;
                    if ($society->email) {
                        $data[$giver->giving]['sender']=$society->email;
                    } else {
                        $data[$giver->giving]['sender']="admin@church.net.za";
                    }
                    $data[$giver->giving]['pg']=$giver->giving;
                    $data[$giver->giving]['pgyr']=$repyr;
                    $data[$giver->giving]['church']=$society->society . " Methodist Church";
                    $data[$giver->giving]['churchabbr']=substr($society->society, 0, 1) . "MC";
                    $data[$giver->giving]['society']=$society->society;
                    $data[$giver->giving]['website']=$society->website;
                    if ($period==1) {
                        $data[$giver->giving]['scope']="month";
                    } else {
                        $data[$giver->giving]['scope']=$period . " months";
                    }
                    $data[$giver->giving]['title']="Planned giving feedback: " . $startofperiod . " to " . $endofperiod;
                    $currentChurchsites=Gift::where('pgnumber', $giver->giving)->where('paymentdate', '>=', $startofperiod)->where('paymentdate', '<=', $endofperiod)->orderBy('paymentdate', 'DESC')->get();
                    foreach ($currentpayments as $cp) {
                        $data[$giver->giving]['current'][]=$cp;
                    }
                    $historicpayments=Gift::where('pgnumber', $giver->giving)->where('paymentdate', '<', $startofperiod)->where('paymentdate', '>=', $repyr . '-01-01')->orderBy('paymentdate', 'DESC')->get();
                    foreach ($historicpayments as $hp) {
                        $data[$giver->giving]['historic'][]=$hp;
                    }
                }
            }
            foreach ($data as $key=>$pg) {
                foreach ($pg['email'] as $indiv) {
                    Mail::to($indiv)->queue(new GivingMail($pg));
                }
            }
        } else {
            $warningdate=date("Y-m-d", $effdate+432000);
            if (in_array($warningdate, $reportdates)) {
                $msg="This is a reminder that your system is configured to send out planned giving emails in 5 days time for the " . 12/$reportnums . " month period ending: " . $warningdate;
                $msg.=".<br><br>If there are any payments for that period that have not yet been captured, you can still add them to the system and they will ";
                $msg.="be included, provided the date of receipt falls within the period being reported.<br><br>Thank you!";
                $warndat=array();
                $warndat['title']="Planned giving reminder";
                $warndat['sender']=$society->email;
                $warndat['body']=$msg;
                $warndat['society']=$society->society;
                $warndat['website']=$society->website;
                Mail::to($administrator)->queue(new SimpleMail($warndat));
            } else {
                // echo "Today is not a report date\n";
            }
        }
    }
}
