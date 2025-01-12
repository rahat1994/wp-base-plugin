<script setup>
import { ref } from 'vue';
import { Accordion, AccordionContent, AccordionItem, AccordionTrigger } from '@/components/ui/accordion'
import {
  Select,
  SelectContent,
  SelectGroup,
  SelectItem,
  SelectLabel,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select'
import { Button } from '@/components/ui/button'
import { Label } from '@/components/ui/label'
import { Switch } from '@/components/ui/switch'
const key = ref("reddit_secrets");

function updateMessage(newKey) {
    key.value = newKey;
}
</script>

<style scoped>
/* Add your styles here */
</style>

<template>
  <div class="hidden h-full flex-col md:flex space-y-2 py-6">
    
      <div class="h-full mx-2">
        <div class="grid h-full items-stretch gap-6 md:grid-cols-[minmax(0,1fr)_200px]">
          <div class="hidden flex-col space-y-4 py-2 sm:flex md:order-2 bg-gray-100 p-4 rounded-lg shadow-md">
            <Accordion type="single" collapsible>
                <AccordionItem value="item-1">
                <AccordionTrigger>Title</AccordionTrigger>
                <AccordionContent>
                    <div class="flex items-center space-x-2 mb-4">
                        <Switch id="airplane-mode" />
                        <Label for="airplane-mode">Show Title</Label>
                    </div>
                    <div class="mb-4">
                        <Select>
                            <SelectTrigger>
                            <SelectValue placeholder="HTML Tag" />
                            </SelectTrigger>
                            <SelectContent>
                            <SelectGroup>
                                <SelectLabel>Tags</SelectLabel>
                                <SelectItem value="div">div</SelectItem>
                                <SelectItem value="span">span</SelectItem>
                                <SelectItem value="p">p</SelectItem>
                                <SelectItem value="h1">h1</SelectItem>
                                <SelectItem value="h2">h2</SelectItem>
                                <SelectItem value="h3">h3</SelectItem>
                                <SelectItem value="h4">h4</SelectItem>
                                <SelectItem value="h5">h5</SelectItem>
                                <SelectItem value="h6">h6</SelectItem>
                            </SelectGroup>
                            </SelectContent>
                        </Select>
                    </div>
                </AccordionContent>
                </AccordionItem>
            </Accordion>
            <Accordion type="single" collapsible>
                <AccordionItem value="item-1">
                <AccordionTrigger>Post Style</AccordionTrigger>
                <AccordionContent>
                    <Label>HTML tag for the title</Label>
                    <Select>
                        <SelectTrigger>
                        <SelectValue placeholder="HTML Tag" />
                        </SelectTrigger>
                        <SelectContent>
                        <SelectGroup>
                            <SelectLabel>Tags</SelectLabel>
                            <SelectItem value="div">div</SelectItem>
                            <SelectItem value="span">span</SelectItem>
                            <SelectItem value="p">p</SelectItem>
                            <SelectItem value="h1">h1</SelectItem>
                            <SelectItem value="h2">h2</SelectItem>
                            <SelectItem value="h3">h3</SelectItem>
                            <SelectItem value="h4">h4</SelectItem>
                            <SelectItem value="h5">h5</SelectItem>
                            <SelectItem value="h6">h6</SelectItem>
                        </SelectGroup>
                        </SelectContent>
                    </Select>
                </AccordionContent>
                </AccordionItem>
            </Accordion>
          </div>
          <div class="md:order-1">
            
              <div class="flex h-full flex-col space-y-4">
                
                <h1 class="text-2xl font-bold mb-4">r/Ecommerce</h1>
                <p class="mb-4">
                    This is a subreddit for ecommerce. You can find a lot of useful information here.
                </p>

                <ul class="list-disc pl-5 space-y-2">
                    <li><a href="https://www.reddit.com/r/ecommerce/" class="text-blue-500 hover:underline">r/Ecommerce</a></li>
                    <li><a href="https://www.reddit.com/r/ecommerce/" class="text-blue-500 hover:underline">r/Ecommerce</a></li>
                    <li><a href="https://www.reddit.com/r/ecommerce/" class="text-blue-500 hover:underline">r/Ecommerce</a></li>
                    <li><a href="https://www.reddit.com/r/ecommerce/" class="text-blue-500 hover:underline">r/Ecommerce</a></li>
                    <li><a href="https://www.reddit.com/r/ecommerce/" class="text-blue-500 hover:underline">r/Ecommerce</a></li>
                </ul>

              </div>
          </div>
        </div>
      </div>
  </div>
</template>

