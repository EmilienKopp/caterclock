
export class FilterService {

    public static Fuzzy(target: object | Array<string>, searchQuery: string) {
        let targetString = "";
        if(!Array.isArray(target)) {
            targetString = Object.values(target).join(" ");
        } else {
            targetString = target.join(" ");
        }

        return FilterService.fuzzyMatch(targetString,searchQuery)
    }

    public static Exact(candidate: any, searchQuery: string) {
        return candidate.first_name.includes(searchQuery) 
            || candidate.last_name.includes(searchQuery)
            || candidate.email.includes(searchQuery)
    }

    public static IgnoreCase(candidate: any, searchQuery: string) {
        return candidate.first_name.toLowerCase().includes(searchQuery.toLowerCase()) 
            || candidate.last_name.toLowerCase().includes(searchQuery.toLowerCase())
            || candidate.email.toLowerCase().includes(searchQuery.toLowerCase())
    }

    public static fuzzyHighlight(targetString: string, searchString: string, highlightClass?: string) {
        const target = targetString?.split('');
        const search = searchString?.split('');
    
        let result = target?.map( (char) => {
            if (search?.map(c => c.toLowerCase()).includes(char.toLowerCase())) {
                return highlightClass 
                    ? `<span class="${highlightClass}">${char}</span>`
                    : `<span style="font-weight-500; background-color:grey;">${char}</span>`;
            }
            return char;
        });
    
        return result?.join('');
    }

    public static exactHighlight(targetString: string, searchString: string, highlightClass?: string) {
        const start = targetString?.toLowerCase()?.indexOf(searchString?.toLowerCase());
        const end = start + searchString?.length;
        
        if(start === -1) return targetString;

        let result = targetString?.split('');
        result.splice(start, 0, `<span class="${highlightClass}">`);
        result.splice(end + 1, 0, "</span>");
    
        return result.join('');
    }

    public static highlight(
        targetString: string, 
        searchString: string,
         highlightClass?: string, 
         type?: "fuzzy" | "exact") 
    {
        switch(type) {
            case "fuzzy":
                return FilterService.fuzzyHighlight(targetString, searchString, highlightClass, type);
            case "exact":
                return FilterService.exactHighlight(targetString, searchString, highlightClass, type);
            default:
                return FilterService.fuzzyHighlight(targetString, searchString, highlightClass, type);
        }
    }

    public static fuzzyMatch(targetString: string, searchString: string) {
        if(!targetString || !searchString) return false;
        const target = targetString?.toLowerCase()?.split('');
        const search = searchString?.toLowerCase()?.split('');
    
        return search?.every( (char) => {
            return target?.includes(char);
        });
    }

}