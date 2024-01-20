<script lang="ts">
    import { twMerge } from 'tailwind-merge';
    import { Button, Dropdown, DropdownItem, Label } from 'flowbite-svelte';
    import { CaretDownFill } from 'svelte-bootstrap-icons';

    export let value: string | number | null = null;
    export let items: any[]  = [];
    export let name: string;
    export let mapping: { labelColumn: string, valueColumn: string };
    export let placeholder: string = "選択してください";
    export let label: string | undefined = undefined;
    export let size: "xs" | "sm" | "md" | "lg" | "xl" | "2xl" = "md";

    let selectedItem: any = null;
    let open: boolean = false;
    let selectedIndex: number = 0;

    let options: {label: string, value: string | number}[] = items?.map(item => {
        return {
            label: item[mapping.labelColumn],
            value: item[mapping.valueColumn]
        }
    });

    const cssClass = twMerge(
        "border-gray-300 rounded-sm dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm",
        $$restProps.class
    );

    const sizes = {
        xs: "px-1 py-1 text-xs w-36",
        sm: "px-2 py-1 text-xs w-40",
        md: "px-3 py-2 text-sm w-48",
        lg: "px-4 py-3 text-base w-56",
        xl: "px-5 py-4 text-lg w-64",
        "2xl": "px-6 py-5 text-xl w-72",
    }


    function handleDropdownSelect(item: any) {
        selectedItem = item;
        open = false;
        value = item.value;
    }

    function handleKeyup(e: Event | KeyboardEvent) {
        const key = (e as KeyboardEvent).key;

        if(key == "ArrowDown") {
            if(selectedIndex < options.length - 1) {
                selectedIndex++;
            }
        } else if(key == "ArrowUp") {
            if(selectedIndex > 0) {
                selectedIndex--;
            }
        } else if(key == "Enter") {
            handleDropdownSelect(options[selectedIndex]);
        }
        selectedItem = options[selectedIndex];
        value = selectedItem.value;

    }


</script>

{#if label}
    <Label for={name}　class="my-0">{label}</Label>
{/if}
<!-- <input type="hidden" name={name} bind:value={value}/>
<Button color="light" class={twMerge("w-full flex justify-between whitespace-nowrap mb-0",sizes[size],$$restProps.class)} on:keyup={handleKeyup}>
    {#if value} 
        {items?.find(item => item[mapping.valueColumn] == value)?.[mapping.labelColumn] ?? placeholder}
    {:else}
        {selectedItem?.label ?? placeholder}
    {/if}
    <CaretDownFill class="inline-block ml-2 justify-self-end"/>
</Button>
<Dropdown bind:open  containerClass={twMerge("-mt-2",$$restProps.class)}>
    {#if $$slots.first}
        <DropdownItem>
            <slot name="first"></slot>
        </DropdownItem>
    {/if}
    
    {#if options !== undefined}
        {#each options ?? [] as item, index}
            <DropdownItem class={twMerge("hover:text-black rounded-md",sizes[size],$$restProps.class)}
                    id="item{name}{index}"
                    on:click={() => handleDropdownSelect(item)}
                    value={item.value}
                >

                {item.label}

            </DropdownItem>
        {/each}
    {:else}
        <slot />
    {/if}
</Dropdown> -->
<select class="bg-transparent rounded p-1 pr-10" bind:value>
    {#each options ?? [] as item, index}
        <option value={item.value}>{item.label}</option>
    {/each}
</select>