<script lang="ts">
    import { Calendar } from "$lib/components/ui/calendar";
    import { getLocalTimeZone, today } from "@internationalized/date";
   
    let value = today(getLocalTimeZone());
  </script>
   
  <Calendar bind:value class="rounded-md border shadow" />