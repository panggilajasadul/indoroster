<div>
    <div x-data="{ 
        getUrl() { return $wire.get('{{ $getStatePath() }}'.replace('.preview', '.media_url')) },
        getType() { return $wire.get('{{ $getStatePath() }}'.replace('.preview', '.media_type')) }
    }">
        <template x-if="getUrl()">
            <div>
                <template x-if="getType() === 'image'">
                    <div class="relative rounded-md overflow-hidden bg-slate-800 border border-slate-700 shadow-md max-w-[80px]">
                        <img :src="getUrl()" class="w-full h-auto object-contain" />
                    </div>
                </template>
                
                <template x-if="getType() === 'video'">
                    <div class="relative rounded-md overflow-hidden bg-black aspect-[9/16] max-w-[80px] border border-slate-700 shadow-md">
                        <video 
                            :src="getUrl()" 
                            class="w-full h-full object-cover"
                            controls
                            muted
                        ></video>
                    </div>
                </template>
            </div>
        </template>
        
        <template x-if="!getUrl()">
            <div class="flex items-center justify-center p-4 border-2 border-dashed border-slate-700 rounded-xl text-slate-500 text-xs italic">
                Masukkan URL media untuk melihat preview
            </div>
        </template>
    </div>
</div>
