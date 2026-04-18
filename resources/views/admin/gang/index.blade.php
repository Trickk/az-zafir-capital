<x-layouts.app :title="__('Gangs')">

<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <p class="az-eyebrow">Network</p>
            <h2 class="az-title text-3xl">Gang Management</h2>
        </div>

        <a href="#" class="az-btn az-btn-primary">
            Create gang
        </a>
    </div>

    <div class="az-card p-6">

        <div class="az-table-wrap">
            <table class="az-table">

                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Holding</th>
                        <th>Dirty Balance</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>

                    <tr>
                        <td>Warriors</td>
                        <td>Golden Mirage</td>
                        <td>$150,000</td>
                        <td>
                            <span class="az-badge az-badge-gold">
                                Active
                            </span>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4" class="az-muted">
                            No additional gangs registered.
                        </td>
                    </tr>

                </tbody>

            </table>
        </div>

    </div>

</div>

</x-layouts.app>
