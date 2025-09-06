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

    /**
     * Boot the component and inject dependencies.
     *
     * @param AffiliateDistanceService   $affiliateDistanceService Service to filter affiliates by distance
     * @param AffiliateFileParserService $affiliateFileParserService Service to parse affiliate files
     *
     * @return void
     */
    public function boot(
        AffiliateDistanceService $affiliateDistanceService,
        AffiliateFileParserService $affiliateFileParserService,
    ): void {
        $this->affiliateDistanceService = $affiliateDistanceService;
        $this->affiliateFileParserService = $affiliateFileParserService;
    }

    /**
     * Handle affiliate file update event.
     *
     * @return void
     */
    public function updatedAffiliateFile(): void
    {
        try {
            $this->validate();
            $this->processFile();
            $this->errorMessage = null;
        } catch (\Exception $e) {
            $this->errorMessage = "Error processing file: " . $e->getMessage();
            $this->affiliateCollection = null;
        }
    }

    /**
     * Render the Livewire component view.
     *
     * @return Factory|View
     */
    public function render(): Factory|View
    {
        return view('livewire.affiliate-file-upload');
    }

    /**
     * Process the uploaded affiliate file.
     *
     * @return void
     */
    protected function processFile(): void
    {
        $affiliatesData = $this->affiliateFileParserService->parseFile(
            $this->affiliateFile->getRealPath()
        );
        $this->affiliateCollection = $this->affiliateDistanceService->filterByDistance(
            $affiliatesData
        );
    }

    /**
     * Get the validation rules for affiliate file upload.
     *
     * @return array[]
     */
    protected function rules(): array
    {
        return [
            'affiliateFile' => ['required', 'file', new TxtFile(), 'max:1024'],
        ];
    }
}
