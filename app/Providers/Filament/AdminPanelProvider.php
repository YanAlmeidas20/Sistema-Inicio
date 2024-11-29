<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Althinect\FilamentSpatieRolesPermissions\FilamentSpatieRolesPermissionsPlugin;
use Spatie\Permission\Traits\HasRoles;
use Filament\Pages;
use Filament\Panel;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationBuilder;
use App\Filament\Resources\PageResource;
use App\Filament\Resources\CategoryResource;
use App\Filament\Resources\AccountPayableResource;
use App\Filament\Resources\AccountReceivableResource; 
use App\Filament\Resources\AgendaResource;
use App\Filament\Resources\EmployeeResource;
use App\Filament\Resources\PartnerResource;
use App\Filament\Resources\UserResource;
use App\Filament\Resources\PatientResource;
use App\Filament\Resources\InventoryResource;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->spa()
            ->brandLogo(asset('image/LogoSages.jpeg'))
            ->maxContentWidth(MaxWidth::ScreenTwoExtraLarge) 
            ->brandLogoHeight('8rem')
            ->colors([
                'primary' => '#3F5BA2',
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
               // Widgets\FilamentInfoWidget::class,
            ])

            ->plugin(FilamentSpatieRolesPermissionsPlugin::make())
            ->navigation(function (NavigationBuilder $builder): NavigationBuilder {
                return $builder->groups([
                    NavigationGroup::make()
                        ->items([
                            NavigationItem::make('Dashboard')
                    ->icon('heroicon-o-home')
                    ->isActiveWhen(fn (): bool => request()->routeIs('filament.admin.pages.dashboard'))
                    ->url(fn (): string => Dashboard::getUrl()),
                        ]),
                    NavigationGroup::make('Administrativo')
                        ->items([
                            ...EmployeeResource::getNavigationItems(),
                            ...PartnerResource::getNavigationItems(),
                            ...UserResource::getNavigationItems(),
                            ...PatientResource::getNavigationItems(),  
                        ]),
                    NavigationGroup::make('Financeiro')
                        ->items([
                            ...AccountPayableResource::getNavigationItems(),
                            ...AccountReceivableResource::getNavigationItems(),
                        ]),
                        NavigationGroup::make('Agendamentos')
                        ->items([
                            ...AgendaResource::getNavigationItems(),
                        ]),
                        NavigationGroup::make('Estoque')
                        ->items([
                            ...InventoryResource::getNavigationItems(),
                        ]),
                        
                        NavigationGroup::make('Configuração')
                        ->items([
                            NavigationItem::make('Roles')
                            ->icon('heroicon-o-user-group')
                            ->isActiveWhen(fn (): bool => request()->routeIs(
                                'filament.admin.resources.pages.roles.index',
                                'filament.admin.resources.pages.roles.create',
                                'filament.admin.resources.pages.roles.edit',
                                'filament.admin.resources.pages.roles.view',
                            ))
                            ->url(fn (): string => 'admin/roles'),
                            NavigationItem::make('Permissions')
                            ->icon('heroicon-o-lock-closed')
                            ->isActiveWhen(fn (): bool => request()->routeIs(
                                'filament.admin.resources.pages.permissions.index',
                                'filament.admin.resources.pages.permissions.create',
                                'filament.admin.resources.pages.permissions.edit',
                                'filament.admin.resources.pages.permissions.view',
                            ))
                            ->url(fn (): string => 'admin/permissions'),
                        ...UserResource::getNavigationItems()
                        ]),
                ]);
            })
            
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
           // FilamentShieldPlugin::make(),
    }
    }