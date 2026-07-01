<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

abstract class ResourceController extends Controller
{
    public function __construct(protected BaseRepositoryInterface $repository)
    {
    }

    abstract protected function viewPath(): string;

    abstract protected function resourceName(): string;

    protected function relations(): array
    {
        return [];
    }

    protected function perPage(): int
    {
        return 12;
    }

    protected function listViewData(Request $request): array
    {
        return [];
    }

    protected function formViewData(?int $id = null): array
    {
        return [];
    }

    public function create(): View
    {
        return view($this->viewPath().'.create', $this->formViewData());
    }

    public function edit(int $id): View
    {
        $item = $this->repository->find($id, $this->relations());

        return view($this->viewPath().'.edit', array_merge([
            'item' => $item,
        ], $this->formViewData($id)));
    }

    public function index(Request $request): View
    {
        $items = $this->repository->paginate($request->string('search')->toString(), $this->perPage(), $this->relations());

        return view($this->viewPath().'.index', array_merge([
            'items' => $items,
            'search' => $request->string('search')->toString(),
        ], $this->listViewData($request)));
    }

    public function show(int $id): View
    {
        $item = $this->repository->find($id, $this->relations());

        return view($this->viewPath().'.show', compact('item'));
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->repository->delete($id);

            return redirect()->route("admin.{$this->resourceName()}.index")
                ->with('success', Str::of($this->resourceName())->replace('-', ' ')->title().' deleted successfully.');
        } catch (\Throwable $throwable) {
            return back()->with('error', $throwable->getMessage());
        }
    }
}
