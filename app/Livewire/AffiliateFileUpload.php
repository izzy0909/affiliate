<?php

namespace App\Livewire;

use App\Rules\TxtFile;
use App\Services\AffiliateDistanceService;
use App\Services\AffiliateFileParserService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;

class AffiliateFileUpload extends Component
{
    use WithFileUploads;

    public ?TemporaryUploadedFile $affiliateFile = null;
    public ?Collection $affiliateCollection = null;
    public ?string $errorMessage = null;
    protected AffiliateDistanceService $affiliateDistanceService;
    protected AffiliateFileParserService $affiliateFileParserService;

    public function boot(
        AffiliateDistanceService $affiliateDistanceService,
        AffiliateFileParserService $affiliateFileParserService,
    ): void
    {
        $this->affiliateDistanceService = $affiliateDistanceService;
        $this->affiliateFileParserService = $affiliateFileParserService;
    }

    public function updatedAffiliateFile(): void
    {
        $this->validate();
        $this->processFile();
    }

    /**
     * @return Factory|View
     */
    public function render()
    {
        return view('livewire.affiliate-file-upload');
    }

    /**
     * @return void
     */
    protected function processFile(): void
    {
        try {
            $affiliatesData = $this->affiliateFileParserService->parseFile($this->affiliateFile->getRealPath());
            $this->affiliateCollection = $this->affiliateDistanceService->filterByDistance($affiliatesData);
        } catch (\Exception $e) {
            $this->errorMessage = "Error processing file: " . $e->getMessage();
            $this->affiliateCollection = null;
        }
    }

    /**
     * @return array[]
     */
    protected function rules(): array
    {
        return [
            'affiliateFile' => ['required', 'file', new TxtFile(), 'max:1024'],
        ];
    }
}
