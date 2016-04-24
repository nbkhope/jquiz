require 'csv'

lesson_numbers = []
question_prompts = []
responses = []
correct_answers = []

puts "Parsing original CSV file . . ."

# Parse the CSV file
CSV.foreach('QuestionBank.csv', col_sep: "\t") do |row|
	#p row
	#p row.class
	lesson_number = row[0]
	question_prompt = row[1]
	responses_description = []
	for index in (2..5)
		responses_description << row[index] unless row[index] == "\\N"
		#p row[index]
	end
	correct_answer = row[6]

	lesson_numbers << lesson_number
	question_prompts << question_prompt
	responses << responses_description
	correct_answers << correct_answer

	#puts "Got: #{lesson_number}, #{question_prompt}, #{responses_description.join(",")}, #{correct_answer}"
end

# p lesson_numbers
# p question_prompts
# p responses
# p correct_answers

unique_lesson_numbers = lesson_numbers.uniq

puts "Generating new CSV files . . ."

puts "Creating lessons.csv"

# Generate lessons.csv
index = 0
CSV.open("lessons.csv", "wb") do |csv|
	while index < unique_lesson_numbers.length
	  csv << [index + 1, unique_lesson_numbers[index]]
	  index += 1
	end
end

puts "Creating questions.csv"

# Generate questions.csv
index = 0
CSV.open("questions.csv", "wb") do |csv|
	while index < question_prompts.length
	  csv << [index + 1, question_prompts[index], unique_lesson_numbers.index(lesson_numbers[index]) + 1	]
	  index += 1
	end
end

puts "Creating responses.csv"

# Generate responses.csv
id_index = 0
count = 1
CSV.open("responses.csv", "wb") do |csv|
	while id_index < responses.length
		correct_answer_as_index = correct_answers[id_index].downcase.codepoints.first - 97
		
		responses[id_index].each.with_index do |response, response_index|
			# Determine whether the current response is the right answer to the question
			if response_index == correct_answer_as_index
				is_right_choice = 1 
			else
				is_right_choice = 0
			end

			csv << [count, response, id_index + 1, is_right_choice]
			count += 1 
	  end

	  id_index += 1
	end
end

puts "[Complete]"
