<div>
    <div x-data="{ 
        getUrl() { return $wire.get('{{ $getStatePath() }}'.replace('.preview', '.media_url')) }
    }">
        <template x-if="getUrl()">
            <div class="relative rounded-md overflow-hidden bg-black aspect-[9/16] max-w-[80px] border border-slate-700 shadow-md">
                <video 
                    :src="getUrl()" 
                    class="w-full h-full object-cover"
                    controls
                    muted
                ></video>
            </div>
        </template>
        <template x-if="!getUrl()">
            <div class="flex items-center justify-center p-2 border-2 border-dashed border-slate-700 rounded-lg text-slate-500 text-[10px] italic leading-tight text-center">
                Preview Video
            </div>
        </template>
    </div>
</div>
