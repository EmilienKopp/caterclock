<script lang="ts">
    
    interface Props {
        checked?: boolean;
        value?: any;
        [key: string]: any
    }

    let { checked = $bindable(false), value = $bindable(null), ...rest }: Props = $props();

</script>


    <input
        type="checkbox"
        bind:checked
        class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
        {...rest}
        />

