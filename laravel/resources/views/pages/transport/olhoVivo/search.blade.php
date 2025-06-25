<x-layout.base>
    <x-slot name="title">Transporte</x-slot>

    <!-- Outros conteúdos da página -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h1 class="h5 mb-2 text-gray-800">{{ $title }}</h1>
            <form 
                class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search w-50"
                action="{{ route('olhoVivo.search') }}"
                method="POST"
            >
                @csrf
                <div class="input-group">
                    <input 
                        type="text" 
                        class="form-control bg-width border-0 small border-left-primary" 
                        placeholder="Buscar Linha..."
                        aria-label="Search" 
                        aria-describedby="basic-addon2"
                        value="{{ $search }}"
                        name="search"
                    >
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>
            @if(!empty($error))
                <div class="alert alert-warning mt-3">
                    <strong>Aviso!</strong> {{ $error }}
                </div>
            @endif
            @if(!empty($success))
                <div class="alert alert-success mt-3">
                    <strong>Sucesso!</strong> {{ $success }}
                </div>
            @endif

            <div class="row mt-4">
                @foreach ($aResponse as $key => $value)
                    <div class="col-md-12 mb-4">
                        <div class=" col-md-12">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $value['name'] }}</div>
                                        </div>
                                        <button 
                                            class="btn bg-gradient-info btn-icon-split mr-2"
                                            type="button"
                                            data-toggle="modal"
                                            data-target="#infoLineModal-{{ $key }}"
                                        >
                                            <span class="icon text-white-50 text-gray-100">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                        </button>
                                        <form action="{{ 
                                            route('olhoVivo.addLine',[
                                                'cl'       => $key,
                                                'lc'       => $value['lc'],
                                                'lt'       => $value['lt'],
                                                'sl'       => $value['sl'],
                                                'tl'       => $value['tl'],
                                                'tp'       => $value['tp'],
                                                'ts'       => $value['ts'],
                                                'name_bus' => $value['name'],
                                            ])
                                        }}" method="POST">
                                            @csrf
                                            <button class="btn bg-gradient-success btn-icon-split mr-2" type="submit">
                                                <span class="icon text-white-50 text-gray-100">
                                                    <i class="fas fa-plus"></i>
                                                </span>
                                            </button>
                                        </form>
                                        <div class="col-auto">
                                            <i class="fas fa-bus fa-2x text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- Info Line --}}
                    <x-layout.modal.olho-vivo.info-line-modal
                        :cl="$key" 
                        :lt="$value['lt']" 
                        :tl="$value['tl']" 
                        :tp="$value['tp']" 
                        :ts="$value['ts']" 
                        :sl="$value['sl']" 
                        :frequency="$frequencies[$value['lt'] . '-' . $value['tl']] ?? []"
                    />
                @endforeach
            </div>
        </div>
        <div class="col-md-6">
            @if (count($myLines) > 0)
                <div class="row">
                    <h1 class="h5 mb-2 text-gray-800">Minhas Linhas</h1>
                </div>
                <div class="row">
                    @foreach($myLines as $line)
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                {{ $line->lt}}-{{ $line->tl }}
                                            </div>
                                            <div class="h7 mb-0 font-weight-bold text-gray-800">
                                                {{ $line->sl == 1 ? $line->tp  : $line->ts }}
                                            </div>
                                        </div>
                                            <button 
                                                class="btn bg-gradient-info btn-icon-split mr-2"
                                                type="button"
                                                data-toggle="modal"
                                                data-target="#infoLineModal-{{ $line->cl }}"
                                            >
                                                <span class="icon text-white-50 text-gray-100">
                                                    <i class="fas fa-info-circle"></i>
                                                </span>
                                            </button>
                                        </button>
                                        <button class="btn bg-gradient-success btn-icon-split mr-2" type="submit">
                                            <span class="icon text-white-50 text-gray-100">
                                                <i class="fas fa-eye"></i>
                                            </span>
                                        </button>
                                        <button 
                                            class="btn bg-gradient-danger btn-icon-split mr-2" 
                                            type="button" 
                                            data-toggle="modal" 
                                            data-target="#removeLineModal-{{ $line->id }}"
                                        >
                                            <span class="icon text-white-50 text-gray-100">
                                                <i class="fas fa-minus"></i>
                                            </span>
                                        </button>
                                        <div class="col-auto">
                                            <i class="fas fa-bus fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                  <x-layout.modal.logout />

                    {{-- Info Line --}}
                    <x-layout.modal.olho-vivo.info-line-modal
                        :cl="$line->cl" 
                        :lt="$line->lt" 
                        :tl="$line->tl" 
                        :tp="$line->tp" 
                        :ts="$line->ts" 
                        :sl="$line->sl" 
                        :frequency="$userFrequencies[$line->lt . '-' . $line->tl] ?? []"
                    />

                    {{-- Remove Line --}}
                    <x-layout.modal.olho-vivo.remove-line-modal 
                        :id="$line->id" 
                        :cl="$line->cl" 
                        :lt="$line->lt" 
                        :tl="$line->tl" 
                        :tp="$line->tp" 
                        :ts="$line->ts" 
                        :sl="$line->sl" 
                    />
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layout.base>