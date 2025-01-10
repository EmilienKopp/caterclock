<script lang="ts">
    interface Props {
        href?: string;
        [key: string]: any
    }

    let { href = "", ...rest }: Props = $props();
</script>

<a
    {href}
    class="line-login-btn flex items-center px-2"
    onclick={() => console.log("Button clicked")}
    {...rest}
>
    <img src="/line_44.png" alt="line login" class="border-r border-black border-opacity-10 mr-3"/>
    Log in with LINE
</a>

<style>
    .line-login-btn {
        border-radius: 4px;
        font-size: 16px;
        color: white;
        background-color: #06c755;
        cursor: pointer;
        font-family: Helvetica, sans-serif;
        transition:
            background-color 0.3s,
            opacity 0.3s;
    }

    .line-login-btn:hover:not(:disabled) {
        background-color: #06c755;
        opacity: 0.9;
    }

    .line-login-btn:active:not(:disabled) {
        background-color: #06c755;
        opacity: 0.7;
    }

    .line-login-btn:disabled {
        background-color: #e5e5e5;
        color: #1e1e1e;
        cursor: not-allowed;
        opacity: 0.6;
    }
</style>
