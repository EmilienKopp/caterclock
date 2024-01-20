<script lang="ts">
    import { twMerge } from "tailwind-merge";

    export let color: "blue" | "red" | "green" | "yellow" | "gray" | "indigo" | "purple" | "pink" | "orange" | "teal" | "cyan" | "white" | "black" = "blue";
    export let type: "button" | "submit" | "reset" = "button";

    const colors = {
        blue: "bg-blue-500 hover:bg-blue-600 text-white",
        red: "bg-red-500 hover:bg-red-600",
        green: "bg-green-500 hover:bg-green-600",
        yellow: "bg-yellow-500 hover:bg-yellow-600",
        gray: "bg-gray-300 hover:bg-gray-600 hover:text-white",
        indigo: "bg-indigo-500 hover:bg-indigo-600",
        purple: "bg-purple-500 hover:bg-purple-600",
        pink: "bg-pink-500 hover:bg-pink-600",
        orange: "bg-orange-500 hover:bg-orange-600",
        teal: "bg-teal-500 hover:bg-teal-600",
        cyan: "bg-cyan-500 hover:bg-cyan-600",
        white: "bg-white hover:bg-gray-200",
        black: "bg-black hover:bg-gray-800 text-white",
    }
</script>

<button {type} class={twMerge("border rounded px-2 max-h-6 bg-slate-300 text-black capitalize",colors[color])} on:click {...$$restProps}>
    <slot></slot>
</button>