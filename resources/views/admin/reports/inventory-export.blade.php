<table>
    <thead>
        <tr>
            <th style="border: 1px solid black; background-color: #acacff; font-weight: 800; text-align: center;" colspan="8">Innovaplas Packaging Corporation</th>
        </tr>
    </thead>
</table>

<table>
    <thead>
        <tr>
            <th style="font-weight: 800;" colspan="2">Generic Product Inventory</th>
        </tr>
        <tr>
            <th style="background-color: #7070fa; color: white; font-weight: 800;">Product Name</th>
            <th style="background-color: #7070fa; color: white; font-weight: 800;">Description</th>
            <th style="background-color: #7070fa; color: white; font-weight: 800;">Price per Item</th>
            <th style="background-color: #7070fa; color: white; font-weight: 800;">Stock Quantity</th>
            <th style="background-color: #7070fa; color: white; font-weight: 800;">Inventory Value</th>
            <th style="background-color: #7070fa; color: white; font-weight: 800;">Last Deducted Date</th>
            <th style="background-color: #7070fa; color: white; font-weight: 800;">Last Restock</th>
            <th style="background-color: #7070fa; color: white; font-weight: 800;">Product Discontinued?</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $key => $product)
            <tr>
                <td style="background-color: {{ ($key + 1) % 2 ? '' : '#dfdff8' }}; border: 1px solid black; width: 100%;">{{ $product->name ?? '' }}</td>
                <td style="background-color: {{ ($key + 1) % 2 ? '' : '#dfdff8' }}; border: 1px solid black; width: 100%;">{{ $product->description ?? '' }}</td>
                <td style="background-color: {{ ($key + 1) % 2 ? '' : '#dfdff8' }}; border: 1px solid black; width: 100%;">{{ $product->price ?? '' }}</td>
                <td style="background-color: {{ ($key + 1) % 2 ? '' : '#dfdff8' }}; border: 1px solid black; width: 100%;">{{ $product->quantity ?? '' }}</td>
                <td style="background-color: {{ ($key + 1) % 2 ? '' : '#dfdff8' }}; border: 1px solid black; width: 100%;">{{ $product->value ?? '' }}</td>
                <td style="background-color: {{ ($key + 1) % 2 ? '' : '#dfdff8' }}; border: 1px solid black; width: 100%;">{{ $product->last_deducted ?? '' }}</td>
                <td style="background-color: {{ ($key + 1) % 2 ? '' : '#dfdff8' }}; border: 1px solid black; width: 100%;">{{ $product->last_restock ?? '' }}</td>
                <td style="background-color: {{ ($key + 1) % 2 ? '' : '#dfdff8' }}; border: 1px solid black; width: 100%;">{{ $product->is_deleted ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

<table>
    <thead>
        <tr>
            <th style="font-weight: 800;" colspan="2">Raw Materials Inventory</th>
        </tr>
        <tr>
            <th style="background-color: #7070fa; color: white; font-weight: 800;">Raw Material Name</th>
            <th style="background-color: #7070fa; color: white; font-weight: 800;">Stock Quantity</th>
            <th style="background-color: #7070fa; color: white; font-weight: 800;">Last Deducted Date</th>
            <th style="background-color: #7070fa; color: white; font-weight: 800;">Last Restock</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $key => $product)
            <tr>
                <td style="background-color: {{ ($key + 1) % 2 ? '' : '#dfdff8' }}; border: 1px solid black; width: 100%;">{{ $product->name ?? '' }}</td>
                <td style="background-color: {{ ($key + 1) % 2 ? '' : '#dfdff8' }}; border: 1px solid black; width: 100%;">{{ $product->quantity ?? '' }}</td>
                <td style="background-color: {{ ($key + 1) % 2 ? '' : '#dfdff8' }}; border: 1px solid black; width: 100%;">{{ $product->last_deducted ?? '' }}</td>
                <td style="background-color: {{ ($key + 1) % 2 ? '' : '#dfdff8' }}; border: 1px solid black; width: 100%;">{{ $product->last_restock ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>