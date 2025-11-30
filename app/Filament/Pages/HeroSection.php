<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;

class HeroSection extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.hero-section';
    protected static ?string $navigationGroup = 'Settings';
    public array|null $media = null;   // <-- REQUIRED property for Filament v3

    protected function getFormSchema(): array{
        return [
            FileUpload::make('media')
                        ->label('Hero image or video')
                        ->visibility('public')
                        ->disk('public')
                        ->directory('hero')
                        ->preserveFilenames()
                        ->required()
                        ->maxSize(50240) // 50 MB; change if you want
                        ->acceptedFileTypes(['image/*', 'video/*'])
                        ->helperText('Upload one file. Allowed types: images and videos.'),
        ];
    }

    public function mount(): void{
        $existing = $this->getExistingHero();
        $this->form->fill([
            'media' => $existing ? ['file' => $existing] : null,
        ]);
    }

    public function save(): void{
        $state = $this->form->getState();
        $newFile = $state['media'] ?? null;
        // Extract stored path if array
        if (is_array($newFile) && isset($newFile['file'])) {
            $newFile = $newFile['file'];
        }
        if ($newFile) {
            // Remove old hero files except the new one
            foreach (Storage::disk('public')->files('hero') as $file) {
                if ($file !== $newFile) {
                    Storage::disk('public')->delete($file);
                }
            }
            Notification::make()
                ->title('Hero saved successfully!')
                ->success()
                ->send();
            return;
        }
        Notification::make()
            ->title('No file uploaded.')
            ->warning()
            ->send();
    }
    private function getExistingHero(): ?string
    {
        $files = Storage::disk('public')->files('hero');
        return $files[0] ?? null;
    }
}
