<?php

namespace Bishopm\Church\Console\Commands;

use Bishopm\Church\Mail\ChurchMail;
use Bishopm\Church\Mail\GivingMail;
use Illuminate\Console\Command;
use Bishopm\Church\Models\Individual;
use Bishopm\Church\Models\Gift;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class GivingEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'church:givingemail';

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
        Log::info('Preparing giving emails on ' . date('Y-m-d H:i'));
        $data=array();
        $today=date('Y-m-d');
        $lagtime=intval(setting('giving.lag_time'));
        // echo "You have a lag setting of " . $lagtime . " days\n";
        $effdate=strtotime($today)-$lagtime*86400;
        // echo "Effdate: " . date("d M Y", $effdate) . "\n";
        $repyr=date('Y', $effdate);
        // echo "Your report year is " . $repyr . "\n";
        $reportnums=intval(setting('giving.reports'));
        // echo "Your system will deliver " . $reportnums . " reports per year\n";
        $administrator=setting('giving.administrator_email');
        $emailbody=setting('giving.email_message');
        $emailending=setting('giving.email_ending');
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
            $givers=Individual::where('giving', '>', 0)->where('email', '<>', '')->get();
            $noemailgivers=Individual::where('giving', '>', 0)->where('email', '')->get();
            $msg="Planned giving emails were sent today to " . count($givers) . " planned givers.";
            if (count($noemailgivers)) {
                $msg.="\n\nThe following people are listed as planned givers but do not have email addresses and may require a hardcopy report:\n\n";
                foreach ($noemailgivers as $nomail) {
                    $msg.=$nomail->firstname . " " . $nomail->surname . "\n\n";
                }
            } else {
                $msg.="\n\nGood news! All planned givers at present have email addresses :)";
            }
            $msg.="\n\nThank you!";
            $nodat=array();
            $nodat['subject']="Planned giving emails sent";
            $nodat['sender']=setting('email.church_email');
            $nodat['firstname']="Planned Giving Administrator";
            $nodat['church']=setting('general.church_name');
            $nodat['email']=$administrator;
            $nodat['body']=$msg;
            Mail::to($nodat['email'])->queue(new ChurchMail($nodat));
            foreach ($givers as $giver) {
                $data[$giver->giving]['email'][]=$giver->email;
                if (count($data[$giver->giving]['email'])==1) {
                    $data[$giver->giving]['period']=$startofperiod . " to " . $endofperiod;
                    if ($nodat['sender']) {
                        $data[$giver->giving]['sender']=$nodat['sender'];
                    } else {
                        $data[$giver->giving]['sender']="admin@church.net.za";
                    }
                    $data[$giver->giving]['pg']=$giver->giving;
                    $data[$giver->giving]['pgyr']=$repyr;
                    $data[$giver->giving]['church']=$nodat['church'];
                    $data[$giver->giving]['churchabbr']=setting('general.church_abbreviation');
                    $data[$giver->giving]['website']='www.westvillemethodist.co.za';
                    if ($period==1) {
                        $data[$giver->giving]['scope']="month";
                    } else {
                        $data[$giver->giving]['scope']=$period . " months";
                    }
                    $data[$giver->giving]['title']="Planned giving feedback: " . $startofperiod . " to " . $endofperiod;
                    $currentpayments=Gift::where('pgnumber', $giver->giving)->where('paymentdate', '>=', $startofperiod)->where('paymentdate', '<=', $endofperiod)->orderBy('paymentdate', 'DESC')->get();
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
                if ($emailbody<>""){
                    $pg['emailbody']=str_replace("[pg]", $pg['pg'], $emailbody);
                    $pg['emailbody']=str_replace("[churchname]", $pg['church'], $pg['emailbody']);
                    $pg['emailbody']=str_replace("[period]", $pg['scope'], $pg['emailbody']);
                    $pg['emailending']=str_replace("[churchname]", $pg['church'], $emailending);
                }
                foreach ($pg['email'] as $indiv) {
                    Mail::to($indiv)->queue(new GivingMail($pg));
                }
            }
        } else {
            $warningdate=date("Y-m-d", $effdate+432000);
            if (in_array($warningdate, $reportdates)) {
                $msg="This is a reminder that your system is configured to send out planned giving emails in " . $lagtime . " days time for the " . 12/$reportnums . " month period ending: " . $warningdate;
                $msg.=". If there are any payments for that period that have not yet been captured, you can still add them to the system and they will ";
                $msg.="be included, provided the date of receipt falls within the period being reported.";
                $warndat=array();
                $warndat['subject']="Planned giving reminder";
                $warndat['body']=$msg;
                $warndat['firstname']="Planned Giving Administrator";
                $warndat['recipient']=$administrator;
                Mail::to($warndat['recipient'])->queue(new ChurchMail($warndat));
            } else {
                // echo "Today is not a report date\n";
            }
        }
        Log::info('Giving emails sent on ' . date('Y-m-d H:i'));
    }
}
