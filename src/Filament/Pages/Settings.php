<?php

namespace Bishopm\Church\Filament\Pages;
 
use Closure;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Bishopm\Church\Filament\Clusters\Settings as SettingsCluster;
use Dotswan\MapPicker\Fields\Map;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TagsInput;
use Outerweb\FilamentSettings\Filament\Pages\Settings as BaseSettings;
 
class Settings extends BaseSettings
{
    protected static ?string $cluster = SettingsCluster::class;

    public function schema(): array|Closure
    {
        return [
            Tabs::make('Settings')
                ->schema([
                    Tabs\Tab::make('General')
                        ->columns(2)
                        ->schema([
                            TextInput::make('general.church_name')->required(),
                            TextInput::make('general.church_abbreviation')->required(),
                            TextInput::make('general.site_logo'),
                            TextInput::make('general.physical_address'),
                            TagsInput::make('general.services'),
                            Map::make('general.map_location')->label('Location')
                        ]),
                    Tabs\Tab::make('Admin')
                        ->columns(2)
                        ->schema([
                            KeyValue::make('admin.agendas')->columnSpanFull(),
                            Textarea::make('admin.bank_details')->columnSpanFull()->rows(4)
                        ]),
                    Tabs\Tab::make('Communication')
                        ->columns(2)
                        ->schema([
                            TextInput::make('communication.church_email'),
                            TextInput::make('communication.church_telephone'),
                            TextInput::make('communication.bulksms_clientid')->label('BulkSMS Client ID'),
                            TextInput::make('communication.bulksms_api_secret')->label('BulkSMS API Secret'),
                            TextInput::make('communication.whatsapp'),
                        ]),
                    Tabs\Tab::make('Email')
                        ->columns(2)
                        ->schema([
                            TextInput::make('email.mailer'),
                            TextInput::make('email.mail_host'),
                            TextInput::make('email.mail_port'),
                            TextInput::make('email.mail_username'),
                            TextInput::make('email.mail_password')->password(),
                            TextInput::make('email.mail_encryption'),
                            TextInput::make('email.mail_from_address'),
                            TextInput::make('email.mail_from_name')
                        ]),
                    Tabs\Tab::make('Features')
                        ->columns(2)
                        ->schema([
                            TextInput::make('features.google_email_address'),
                            TextInput::make('features.google_books_key')
                        ]),                    
                    Tabs\Tab::make('Website')
                        ->columns(2)
                        ->schema([
                            TextInput::make('website.youtube_channel'),
                            TextInput::make('website.facebook_page'),
                            TextInput::make('website.youversion_page'),
                            TextInput::make('website.instagram_account'),
                            TextInput::make('website.twitter_feed'),
                            TextInput::make('website.text_logo'),
                            TextInput::make('website.mapbox_token'),
                        ]),
                    Tabs\Tab::make('Worship')
                        ->columns(2)
                        ->schema([
                            TextInput::make('worship.worship_email'),
                            TextInput::make('worship.society_id')->label('methodist.church.net.za society ID'),
                            KeyValue::make('worship.order_of_service')->columnSpanFull()                            
                        ]),
                ]),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Site settings';
    }
}