<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center flex-wrap">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Mailtrap Settings') }}
            </h2>
            <a href="{{ route('mailtrap-settings.edit') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2 sm:mt-0">
                {{ __('Edit Settings') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="bg-green-500 text-white p-4 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="bg-red-500 text-white p-4 rounded mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Setting
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Value
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        MAIL_MAILER
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $settings->mailer }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        MAIL_HOST
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $settings->host }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        MAIL_PORT
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $settings->port }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        MAIL_USERNAME
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $settings->username }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        MAIL_PASSWORD
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $settings->password }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        MAIL_ENCRYPTION
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $settings->encryption }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        MAIL_FROM_ADDRESS
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $settings->from_address }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        MAIL_FROM_NAME
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $settings->from_name }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>