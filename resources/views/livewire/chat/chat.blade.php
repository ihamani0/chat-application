<div class="fixed  h-full  flex bg-white border  lg:shadow-sm inset-0 lg:top-16  lg:inset-x-2 m-auto lg:h-[90%] rounded-t-lg overflow-y-auto">
    
    
    <div class="hidden lg:flex relative w-full md:w-[320px] xl:w-[400px] shrink-0 h-full border overflow-y-auto" >

        <livewire:chat.chat-list :selectedConvrsation="$selectedConvrsation" :query="$query">
    </div>

    <div class="grid   w-full border-l h-full relative overflow-y-auto" style="contain:content">

        

            <livewire:chat.chat-box :selectedConvrsation="$selectedConvrsation">

        

    </div>

</div>