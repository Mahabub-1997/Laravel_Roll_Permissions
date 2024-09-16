<x-app-layout>
    <x-slot name="header">
       <div class="flex justify-between">
           <h2 class="font-semibold text-xl text-gray-800 leading-tight">
               {{ __('permissions') }}
           </h2>
           <a href="{{route('permissions.create')}}" class="bg-slate-700 text-sm rounded-md text-white px-3 py-3 ">Create</a>
       </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
           <x-massage>

           </x-massage>
            <table class="w-full">
               <thead class="bg-gray-50">
               <tr class="border-b">
                   <th class="px-6 py-3 text-left">#</th>
                   <th class="px-6 py-3 text-left">Name</th>
                   <th class="px-6 py-3 text-left">Created</th>
                   <th class="px-6 py-3 text-center" >Action</th>
               </tr>
               </thead>
                <tbody class="bg-white">
                   @if($permissions->isNotEmpty())
                       @foreach($permissions as $key=>$permission)
                           <tr class="border-b">
                               <td class="px-6 py-3 text-left">
                                   {{++$key}}
                               </td>
                               <td class="px-6 py-3 text-left">
                                   {{$permission->name}}
                               </td>
                               <td class="px-6 py-3 text-left">
                                   {{ \Carbon\Carbon::parse($permission->created_at)->format('d M, Y')}}
                               </td>
                               <td class="px-6 py-3 text-center">
                                   <a href="{{route('permissions.edit',$permission->id)}}" class="bg-slate-700 text-sm rounded-md text-white px-3 py-3 hover:bg-slate-700">Edit</a>
                                   <a href="{{route('permissions.destroy',$permission->id)}}" onclick="return confirm('Are You Want To Delete this!!!')" class="bg-red-600 text-sm rounded-md text-white px-3 py-3 hover:bg-red-700">Delete</a>
{{--                                   <a href="javascript:void(0)" onclick="deletePermission({{$permission->id}})" class="bg-slate-700 text-sm rounded-md text-white px-3 py-3 hover:bg-slate-700></a>--}}
                               </td>
                           </tr>
                       @endforeach
                   @endif
                </tbody>
            </table>

         <div class="my-3">
             {{$permissions->links()}}
         </div>
        </div>
    </div>
{{--            <x-slot name="script">--}}
{{--                <script type="text/javascript">--}}
{{--                    function deletePermission($id){--}}
{{--                        if(confirm("Are You Sure You Want To Delete?")){--}}
{{--                            $.ajax({--}}
{{--                                url : '{{route("permissions.destroy")}}',--}}
{{--                                type : 'delete',--}}
{{--                                data: {id:id},--}}
{{--                                dataType:'json',--}}
{{--                                    headers: {--}}
{{--                                              'x-csrf-token' :'{ csrf_token}'--}}
{{--    --}}
{{--    },--}}

{{--                                success: function (response) {--}}
{{--                                   window.location.href =  '{{route("permissions.index")}}'--}}

{{--                                }--}}
{{--                            })--}}
{{--                        }--}}
{{--                    }--}}
{{--                </script>--}}

{{--            </x-slot>--}}

</x-app-layout>
